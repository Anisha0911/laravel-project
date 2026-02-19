@php
    // Determine the correct update route based on role
    $profileUpdateRoute = auth()->user()->role === 'admin'
        ? route('admin.profile.update')
        : route('user.profile.update');
@endphp

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0 pb-0">
        <h5 class="fw-bold mb-1">Profile Information</h5>
        <p class="text-muted small mb-0">
            Update your account profile information and email address.
        </p>
    </div>

    <div class="card-body">
        <!-- Email Verification Form -->
        <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <!-- Profile Update Form -->
        <form method="POST" action="{{ $profileUpdateRoute }}" class="mt-3">
            @csrf
            @method('PATCH')

            <!-- Name -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Full Name</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       class="form-control form-control-lg"
                       required autofocus>

                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Email Address</label>
                <input type="email"
                       name="email"
                       value="{{ old('email', $user->email) }}"
                       class="form-control form-control-lg"
                       required>

                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="alert alert-warning mt-3 p-2">
                        <small>
                            Your email is not verified.
                            <button form="send-verification" class="btn btn-link p-0 fw-semibold">
                                Re-send verification email
                            </button>
                        </small>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2 p-2">
                            Verification link sent!
                        </div>
                    @endif
                @endif
            </div>

            <!-- Save Button -->
            <div class="d-flex align-items-center gap-3 mt-4">
                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">
                    <i class="bi bi-save me-1"></i> Save Changes
                </button>

                @if (session('status') === 'profile-updated')
                    <span class="text-success fw-semibold">✔ Saved successfully</span>
                @endif
            </div>

        </form>
    </div>
</div>
