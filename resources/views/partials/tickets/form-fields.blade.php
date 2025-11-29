<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">
            Full Name <span class="required-mark">*</span>
        </label>
        <input 
            type="text" 
            class="form-control @error('name') is-invalid @enderror" 
            id="name" 
            name="name" 
            value="{{ old('name') }}"
            placeholder="Enter your full name"
            required
        >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="email" class="form-label">
            Email Address <span class="required-mark">*</span>
        </label>
        <input 
            type="email" 
            class="form-control @error('email') is-invalid @enderror" 
            id="email" 
            name="email" 
            value="{{ old('email') }}"
            placeholder="your.email@example.com"
            required
        >
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="mb-3">
    <label for="type" class="form-label">
        Ticket Type <span class="required-mark">*</span>
    </label>
    <select 
        class="form-select @error('type') is-invalid @enderror" 
        id="type" 
        name="type"
        required
    >
        <option value="">-- Select a ticket type --</option>
        @foreach($ticketTypes as $value => $label)
            <option value="{{ $value }}" {{ old('type') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('type')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="subject" class="form-label">
        Subject <span class="required-mark">*</span>
    </label>
    <input 
        type="text" 
        class="form-control @error('subject') is-invalid @enderror" 
        id="subject" 
        name="subject" 
        value="{{ old('subject') }}"
        placeholder="Brief description of your issue"
        required
    >
    @error('subject')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="description" class="form-label">
        Description <span class="required-mark">*</span>
    </label>
    <textarea 
        class="form-control @error('description') is-invalid @enderror" 
        id="description" 
        name="description" 
        rows="6"
        placeholder="Please provide detailed information about your issue..."
        required
    >{{ old('description') }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted">Minimum 10 characters</small>
</div>