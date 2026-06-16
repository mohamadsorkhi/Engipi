@props(['skill'])

<div class="btn-group">
    <span class="btn btn-light disabled" aria-current="page">{{ $skill->name }}</span>
    <form action="{{ route('specialist.skills.destroy', $skill->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm ajax-submit" data-confirm="آیا از حذف این مهارت مطمئن هستید؟">
            <i class="ri-delete-bin-line"></i>
        </button>
    </form>
</div>
