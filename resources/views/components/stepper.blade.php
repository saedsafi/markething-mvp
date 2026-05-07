<div class="stepper">

    @foreach ($steps as $index => $step)

        <div class="stepper-item {{ $index + 1 < $active ? 'done' : '' }} {{ $index + 1 == $active ? 'active' : '' }}">
            <span>{{ $index + 1 }}</span>
            <p>{{ $step }}</p>
        </div>

    @endforeach

</div>