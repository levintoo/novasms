 <div>
        <h2 class="underline">Validation errors</h2>
        @forelse($errors as $error)
            <p>
                row: {{ $error['row'] }}
            </p>
            <p>
                field: {{ $error['attribute'] }}
            </p>
            @forelse($error['errors'] as $error)
                <p style="color: #ef4444">
                    {{ $error }}
                </p>
            @empty
            @endforelse
        @empty
        @endforelse
    </div>
