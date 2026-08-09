<section id="program" class="section program">
  <div class="wrap">
    <h2 class="display h2">Program</h2>
    <p class="program-lead">{{ config('festival.program_lead') }}</p>
    <div>
      @foreach (config('festival.program') as $row)
        <div class="prow">
          <span class="ptime">{{ $row['time'] }}</span>
          <span class="pact">{{ $row['act'] }}</span>
          @if ($row['stage'])
            <span class="pstage @if($row['stage_class']) {{ $row['stage_class'] }} @endif">{{ $row['stage'] }}</span>
          @endif
        </div>
      @endforeach
    </div>
  </div>
</section>
