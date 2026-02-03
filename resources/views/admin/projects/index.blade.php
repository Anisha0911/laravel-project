@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
            Projects
        </h2>

        <a href="{{ route('admin.projects.create') }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700
                  text-white px-4 py-2 rounded-lg shadow">
            + Add Project
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left">Project Name</th>
                        <th class="px-6 py-3 text-left">Owner</th>
                        <th class="px-6 py-3 text-left">Duration</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y dark:divide-gray-700">
                    @forelse($projects as $project)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 font-medium text-gray-800 dark:text-white">
                                {{ $project->name }}
                            </td>

                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $project->user->name ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                                {{ $project->start_date ?? 'N/A' }}
                                <span class="mx-1">→</span>
                                {{ $project->end_date ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-4 text-right space-x-3">
                                <a href="{{ route('admin.projects.edit', $project->id) }}"
                                   class="text-indigo-600 hover:underline">
                                    Edit
                                </a>

                                <form action="{{ route('admin.projects.destroy', $project->id) }}"
                                      method="POST"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            onclick="return confirm('Are you sure?')"
                                            class="text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                No projects found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

@endsection
