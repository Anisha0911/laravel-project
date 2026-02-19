<section class="card border-0 shadow-sm rounded-4 mt-4">
    <div class="card-header bg-white border-0 pb-0">
        <h5 class="fw-bold mb-1">Update Password</h5>
        <p class="text-muted small mb-0">
            Use a strong password to keep your account secure.
        </p>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('password.update') }}" class="mt-3">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Current Password</label>
                <input type="password"
                       name="current_password"
                       class="form-control form-control-lg"
                       autocomplete="current-password">

                @error('current_password', 'updatePassword')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- New Password -->
            <div class="mb-3">
                <label class="form-label fw-semibold">New Password</label>
                <input type="password"
                       name="password"
                       class="form-control form-control-lg"
                       autocomplete="new-password">

                @error('password', 'updatePassword')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Confirm New Password</label>
                <input type="password"
                       name="password_confirmation"
                       class="form-control form-control-lg"
                       autocomplete="new-password">

                @error('password_confirmation', 'updatePassword')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Save Button -->
            <div class="d-flex align-items-center gap-3 mt-4">
                <button type="submit" class="btn btn-warning px-4 py-2 rounded-pill">
                    <i class="bi bi-shield-lock me-1"></i> Update Password
                </button>

                @if (session('status') === 'password-updated')
                    <span class="text-success fw-semibold">✔ Password updated</span>
                @endif
            </div>
        </form>
    </div>
</section>
