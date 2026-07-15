<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\Project;
use App\Models\Request as CollaborationRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MessagePolicy
{
    public function viewConversation(User $user, User $participant): Response
    {
        if ($user->is($participant)) {
            return Response::deny('نمی‌توانید با خودتان پیام تبادل کنید.');
        }

        if ($this->hasExistingConversation($user, $participant)
            || $this->hasEligibleProjectRelationship($user, $participant)) {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    }

    public function sendMessage(User $user, User $recipient, ?Project $project = null): Response
    {
        if ($user->is($recipient)) {
            return Response::deny('نمی‌توانید به خودتان پیام بفرستید.');
        }

        $isAllowed = $project
            ? $this->hasEligibleProjectRelationship($user, $recipient, $project)
            : $this->hasExistingConversation($user, $recipient)
                || $this->hasEligibleProjectRelationship($user, $recipient);

        return $isAllowed
            ? Response::allow()
            : Response::deny('امکان ارسال پیام به این کاربر وجود ندارد.');
    }

    private function hasExistingConversation(User $user, User $participant): bool
    {
        return Message::query()
            ->where(function ($query) use ($user, $participant) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $participant->id);
            })
            ->orWhere(function ($query) use ($user, $participant) {
                $query->where('sender_id', $participant->id)
                    ->where('receiver_id', $user->id);
            })
            ->exists();
    }

    private function hasEligibleProjectRelationship(
        User $user,
        User $participant,
        ?Project $project = null
    ): bool {
        $query = CollaborationRequest::query()
            ->whereIn('status', ['pending', 'accepted']);

        if ($project) {
            $query->where('project_id', $project->id);

            if ($project->employer_id === $user->id) {
                return $query->where('user_id', $participant->id)->exists();
            }

            if ($project->employer_id === $participant->id) {
                return $query->where('user_id', $user->id)->exists();
            }

            return false;
        }

        return $query
            ->where(function ($relationship) use ($user, $participant) {
                $relationship
                    ->where(function ($employerToApplicant) use ($user, $participant) {
                        $employerToApplicant
                            ->where('user_id', $participant->id)
                            ->whereHas('project', function ($projectQuery) use ($user) {
                                $projectQuery->where('employer_id', $user->id);
                            });
                    })
                    ->orWhere(function ($applicantToEmployer) use ($user, $participant) {
                        $applicantToEmployer
                            ->where('user_id', $user->id)
                            ->whereHas('project', function ($projectQuery) use ($participant) {
                                $projectQuery->where('employer_id', $participant->id);
                            });
                    });
            })
            ->exists();
    }
}
