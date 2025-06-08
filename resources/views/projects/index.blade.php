@extends('layouts.admin')

@section('title', 'Project Management')
@section('page_title', 'Your Projects')

@push('styles')
<style>
    .modal-body .detail-section {
        margin-bottom: 1.5rem;
    }
    .modal-body .detail-label {
        font-weight: 600;
        color: #667085; /* Gray-500 */
        display: block;
        margin-bottom: 0.25rem;
    }
    .modal-body .detail-content {
        white-space: pre-wrap; /* Allows text to wrap */
        word-break: break-word;
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">All Projects</h3>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">Create New Project</a>
    </div>

    {{-- Desktop View: Table --}}
    <div class="table-responsive d-none d-lg-block">
        <table class="table card-table table-vcenter text-nowrap datatable">
            <thead>
                <tr>
                    <th>Channel Name</th>
                    <th>Status</th>
                    <th>Date Created</th>
                    <th class="w-1">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                    <tr>
                        <td>
                            <a href="#" class="text-reset view-project-btn" data-bs-toggle="modal" data-bs-target="#project-details-modal" data-project='@json($project)'>
                                {{ $project->channel_name_final }}
                            </a>
                        </td>
                        <td>
                            @if ($project->is_active)
                                <span class="badge bg-green-lt">Active</span>
                            @else
                                <span class="badge bg-gray-lt">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $project->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-primary me-2">Edit</a>
                                <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">You don't have any projects yet. Please create one!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Mobile View: Cards --}}
    <div class="d-lg-none">
        @forelse ($projects as $project)
            <div class="card m-3">
                <div class="card-body">
                    <h3 class="card-title">
                        <a href="#" class="text-reset view-project-btn" data-bs-toggle="modal" data-bs-target="#project-details-modal" data-project='@json($project)'>
                            {{ $project->channel_name_final }}
                        </a>
                    </h3>
                    <div class="text-muted mb-2">
                        Created: {{ $project->created_at->format('d M Y') }}
                    </div>
                    @if ($project->is_active)
                        <span class="badge bg-green-lt">Active</span>
                    @else
                        <span class="badge bg-gray-lt">Inactive</span>
                    @endif
                    <div class="d-flex mt-3">
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-sm btn-outline-primary w-50 me-2">Edit</a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="w-50" onsubmit="return confirm('Are you sure you want to delete this project?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center p-4">You don't have any projects yet. Please create one!</div>
        @endforelse
    </div>

    <div class="card-footer d-flex align-items-center">
        {{ $projects->links() }}
    </div>
</div>


{{-- Project Details Modal --}}
<div class="modal modal-blur fade" id="project-details-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-title">Project Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {{-- Content will be injected by JavaScript --}}
        <div id="modal-content-placeholder">
            <div class="text-center">Loading details...</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-bs-dismiss="modal">Close</button>
        <a href="#" id="modal-edit-btn" class="btn btn-primary">Edit Project</a>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = new bootstrap.Modal(document.getElementById('project-details-modal'));
    const modalTitle = document.getElementById('modal-title');
    const modalContent = document.getElementById('modal-content-placeholder');
    const modalEditBtn = document.getElementById('modal-edit-btn');
    
    document.querySelectorAll('.view-project-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const projectData = JSON.parse(this.dataset.project);

            // Update modal title
            modalTitle.textContent = projectData.channel_name_final || 'Project Details';
            
            // Update modal edit button link
            modalEditBtn.href = '{{ url("projects") }}/' + projectData.id + '/edit';
            
            // Build and inject modal content
            let htmlContent = '';
            
            // Helper function to create detail sections
            const createSection = (title, data) => {
                let sectionHtml = `<div class="detail-section"><h4>${title}</h4>`;
                for (const [key, value] of Object.entries(data)) {
                    if (value) {
                         sectionHtml += `<span class="detail-label">${key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</span>
                                       <p class="detail-content">${typeof value === 'object' ? JSON.stringify(value, null, 2) : value}</p>`;
                    }
                }
                sectionHtml += '</div>';
                return sectionHtml;
            };

            htmlContent += createSection('Channel Identity', {
                'Channel Name': projectData.channel_name_final,
                'Business Email': projectData.business_email,
                'YouTube Link': projectData.youtube_channel_link,
                'Twitter Handle': projectData.social_handle_twitter,
                'Threads Handle': projectData.social_handle_threads,
                'LinkedIn Profile': projectData.social_handle_linkedin,
            });

            htmlContent += createSection('Foundation & Vision', {
                'Channel Description': projectData.channel_description,
                'Long Term Vision': projectData.long_term_vision,
                'Channel Mission': projectData.channel_mission,
            });
            
            htmlContent += createSection('Target Audience', {
                'Primary Audience': projectData.primary_audience_persona,
                'Secondary Audience': projectData.secondary_audience_persona,
            });
            
            // Add more sections as needed
            // ...

            modalContent.innerHTML = htmlContent;
        });
    });
});
</script>
@endpush
