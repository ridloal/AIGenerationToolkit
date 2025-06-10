@extends('layouts.admin')

@section('title', 'Content Plans')
@section('page_title', 'Content for: ' . $project->channel_name_final)

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Content Plans</h3>
        <a href="{{ route('projects.contents.create', $project) }}" class="btn btn-primary">Create New Content Plan</a>
    </div>
    <div class="table-responsive">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Format</th>
                    <th>Keywords</th>
                    <th>Created On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contents as $content)
                    <tr>
                        <td>{{ Str::limit($content->title, 50) }}</td>
                        <td>
                            <span class="badge bg-{{ $content->video_format === 'long' ? 'blue' : 'purple' }}-lt">
                                {{ ucfirst($content->video_format) }}
                            </span>
                        </td>
                        <td>{{ Str::limit($content->target_keywords, 40) }}</td>
                        <td>{{ $content->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('contents.edit', $content) }}" class="btn btn-sm btn-outline-primary me-2">Edit</a>
                                <form action="{{ route('contents.destroy', $content) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this content plan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No content plans created for this project yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex align-items-center">
        {{ $contents->links() }}
    </div>
</div>
@endsection
