@extends('layouts.app')

@section('content')
<div class="category-section">
    <h2>カテゴリ</h2>

    @foreach ($hinmeis as $hinmei)
        <div class="hinmei-block">
            <strong class="hinmei-title">
                {{ $hinmei->HINMEI_NAME }}
            </strong>

            <div class="ryoiki-labels">
                @foreach ($hinmei->ryoikis as $ryoiki)
                    @php
                        $toolCount = $ryoiki->tools()->where('RYOIKI', $hinmei->HINMEI_CODE)->count();
                    @endphp

                    @if ($toolCount > 0)
                        <a href="{{ route('tools.search', ['ryoiki' => $ryoiki->RYOIKI_CODE]) }}" class="ryoiki-badge">
                            {{ $ryoiki->RYOIKI_NAME }}（{{ $toolCount }}）
                        </a>
                    @else
                        <span class="ryoiki-badge empty">
                            {{ $ryoiki->RYOIKI_NAME }}（0）
                        </span>
                    @endif
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
