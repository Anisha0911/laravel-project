@php
    // Determine the correct delete route based on role
    $profileDestroyRoute = auth()->user()->role === 'admin'
        ? route('admin.profile.destroy')
        : route('user.profile.destroy');
@endphp

<div class="card border-0 shadow-sm rounded-4 mt-4 border-danger">

    <div class="card-header bg-white border-0 pb-0">
        <h5 class="fw-bold text-danger mb-1">Delete Account</h5>
        <p class="text-muted small mb-0">
            Once your account is deleted, all data will be permanently removed. This action cannot be undone.
        </p>
    </div>

    <div class="card-body">

        <!-- Delete Button -->
        <button class="btn btn-outline-danger rounded-pill px-4 py-2"
                x-data
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            <i class="bi bi-trash me-1"></i> Delete My Account
        </button>

        <!-- Confirm Delete Modal -->
        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="POST" action="{{ $profileDestroyRoute }}" class="p-4">
                @csrf
                @method('DELETE')

                <h5 class="fw-bold text-danger mb-2">
                    Are you sure you want to delete your account?
                </h5>

                <p class="text-muted small mb-3">
                    This action is permanent. Enter your password to confirm deletion.
                </p>

                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password"
                           name="password"
                           class="form-control form-control-lg"
                           placeholder="Enter your password">

                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-end gap-3">
                    <button type="button"
                            class="btn btn-light px-4 rounded-pill"
                            x-on:click="$dispatch('close')">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-danger px-4 rounded-pill">
                        <i class="bi bi-trash me-1"></i> Delete Account
                    </button>
                </div>

            </form>
        </x-modal>

    </div>
</div>
