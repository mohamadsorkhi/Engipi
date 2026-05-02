<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\QuestionnaireSection;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ConsultationController extends Controller
{
    public function index()
    {
        $service = Service::where('is_active', 1)->firstOrFail();
        $user = Auth::user();
        $consultation = Consultation::where('user_id', $user->id)
            ->where('service_id', $service->id)
            ->where('status', 'draft')
            ->latest()
            ->first();

        return view('user.consultations.index', compact('service', 'consultation'));
    }

    public function start(Service $service)
    {
        $user = Auth::user();
        $questionnaire = $service->questionnaires()->where('is_active', 1)->latest()->firstOrFail();
        $consultation = Consultation::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'questionnaire_id' => $questionnaire->id,
            'status' => 'draft',
        ]);

        return redirect()->route('user.consultations.show', $consultation);
    }

    public function show(Consultation $consultation)
    {
        if ($consultation->status !== 'draft') {
            $consultation->load('questionnaire.sections.questions.options', 'answers');
            $answers = $consultation->answers->keyBy('question_id');
            return view('user.consultations.completed', compact('consultation', 'answers'));
        }

        $questionnaire = $consultation->questionnaire()->with(['sections' => fn ($q) => $q->orderBy('sort_order')])->first();
        $currentSection = $questionnaire->sections->first();

        return view('user.consultations.show', compact('consultation', 'questionnaire', 'currentSection'));
    }

    private function renderStep(Consultation $consultation, $sectionId)
    {
        $questionnaire = $consultation->questionnaire()->with(['sections' => fn ($q) => $q->orderBy('sort_order')])->first();
        $currentSection = $questionnaire->sections->find($sectionId);
        if (!$currentSection) {
            // This could mean the end of the questionnaire
            return $this->renderFinalStep($consultation);
        }

        $answers = $consultation->answers()->whereIn('question_id', $currentSection->questions->pluck('id'))->get()->keyBy('question_id');
        $totalSteps = $questionnaire->sections->count();
        $currentStepIndex = $questionnaire->sections->pluck('id')->search($sectionId);

        $progressHtml = view('user.consultations.partials.progress', compact('currentSection', 'totalSteps', 'currentStepIndex'))->render();
        $stepHtml = view('user.consultations.partials.step', compact('consultation', 'questionnaire', 'currentSection', 'answers'))->render();

        return response()->json([
            'wizard_container' => '#wizard-container',
            'progress_container' => '#wizard-progress-container',
            'html' => $stepHtml . $progressHtml,
        ]);
    }

    private function renderFinalStep(Consultation $consultation)
    {
        $html = view('user.consultations.partials.final_step', compact('consultation'))->render();
        return response()->json([
            'wizard_container' => '#wizard-container',
            'html' => $html,
        ]);
    }


    public function saveStep(Request $request, Consultation $consultation)
    {
        $sectionId = $request->input('section_id');
        $direction = $request->input('direction', 'next');

        $questionnaire = $consultation->questionnaire()->with(['sections' => fn ($q) => $q->orderBy('sort_order')])->first();
        $sections = $questionnaire->sections;
        $currentSectionIndex = $sections->pluck('id')->search($sectionId);

        if ($direction === 'previous') {
            $previousSection = $sections->get($currentSectionIndex - 1);
            return $this->renderStep($consultation, $previousSection->id);
        }

        // --- Validation and Saving for 'next' direction ---
        $section = $sections->find($sectionId);
        $rules = [];
        $attributes = [];

        foreach ($section->questions as $question) {
            $fieldName = 'answers.' . $question->id;
            $rule = $question->is_required ? ['required'] : ['nullable'];
            if ($question->type === 'number' && $question->is_required) $rule[] = 'numeric';
            if ($question->type === 'multi-select' && $question->is_required) $rule = ['required', 'array', 'min:1'];

            $rules[$fieldName] = $rule;
            $attributes[$fieldName] = '"' . $question->label . '"';
        }

        $validator = Validator::make($request->all(), $rules, [], $attributes);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        foreach ($validator->validated()['answers'] ?? [] as $questionId => $value) {
            $consultation->answers()->updateOrCreate(['question_id' => $questionId], ['value' => is_array($value) ? json_encode($value) : $value]);
        }

        $nextSection = $sections->get($currentSectionIndex + 1);
        return $this->renderStep($consultation, $nextSection->id);
    }

    public function submit(Request $request, Consultation $consultation)
    {
        $consultation->update(['status' => 'submitted']);
        return response()->json(['redirect' => route('user.consultations.show', $consultation)]);
    }
}
