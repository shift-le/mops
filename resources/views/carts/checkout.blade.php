@extends('layouts.app')

@section('content')

<div class="checkout-main">
    @if ($errors->any())
    <div class="cart-error-message">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <h2 class="checkout-title">依頼主届け先入力</h2>
    <div class="checkout-form-wrapper">
        <h3 class="checkout-section-title">依頼主情報</h3>
        <div class="checkout-section">
            <div class="checkout-row">
                <label class="checkout-label">依頼主</label>
                <input type="text" class="checkout-input" value="{{ $soshiki1->SOSHIKI1_NAME ?? '' }}" readonly>
                <input type="text" class="checkout-input" value="{{ $soshiki2->SOSHIKI2_NAME ?? '' }}" readonly>
                <input type="text" class="checkout-input" value="{{ $user->NAME ?? 'なし' }}" readonly>
            </div>
            <div class="checkout-row">
                <label class="checkout-label">部署名</label>
                <input type="text" class="checkout-input" value="{{ $soshiki1->SOSHIKI1_NAME ?? '' }} {{ $soshiki2->SOSHIKI2_NAME ?? '' }}" readonly>
            </div>
            <div class="checkout-row">
                <label class="checkout-label">住所</label>
                @php
                $postcode = $soshiki1->POST_CODE ?? '';
                $formattedPostcode = (strlen($postcode) === 7) ? substr($postcode, 0, 3) . '-' . substr($postcode, 3) : $postcode;
                @endphp
                <input type="text" class="checkout-input" value="〒{{ $formattedPostcode }} {{ $soshiki1->ADDRESS1 ?? '' }} {{ $soshiki1->ADDRESS2 ?? '' }} {{ $soshiki1->ADDRESS3 ?? '' }}" readonly>
            </div>
            <div class="checkout-row">
                <label class="checkout-label">電話番号</label>
                <input type="text" class="checkout-input" value="{{ $soshiki1->TEL ?? '' }}" readonly>
                <label class="checkout-label">FAX</label>
                <input type="text" class="checkout-input-sub" value="{{ $soshiki1->FAX ?? '' }}" readonly>
            </div>
        </div>
    </div>

    <div class="checkout-form-wrapper">
        <h3 class="checkout-section-title">届け先情報</h3>
        <form class="checkout-form" method="POST" action="{{ route('carts.checkout') }}">
            @csrf
            <div class="checkout-section">
                <div class="checkout-row">
                    <label class="checkout-label">届け先</label>
                    <select id="delivery_select" name="DELIVERY_SELECT" class="checkout-input" style="background-color: white; min-width: 300px;" onchange="this.form.submit()">
                    <optgroup label="駐在先事業所">
                            @foreach($soshiki2List as $code => $name)
                            <option value="soshiki2_{{ $code }}" {{ old('delivery_select', $selected ?? '') == "soshiki2_$code" ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="駐在員">
                            @foreach($userList as $id => $name)
                            <option value="user_{{ $id }}" {{ old('delivery_select', $selected ?? '') == "user_$id" ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="checkout-row">
                    <label class="checkout-label">届け先名称</label>
                    <input type="text" class="checkout-input" name="delivery_name" style="background-color: white; min-width: 273px;" value="{{ old('delivery_name', $delivery_name ?? '') }}">
                </div>
                <div class="checkout-row">
                    <label class="checkout-label">郵便番号</label>
                    @php
                    $postCode = $delivery_data['POST_CODE'] ?? '';
                    $formattedPostCode = (strlen($postCode) === 7) ? substr($postCode, 0, 3) . '-' . substr($postCode, 3) : $postCode;
                    @endphp
                    <input type="text" class="checkout-input" value="{{ $formattedPostCode }}" readonly>
                    <input type="hidden" name="POST_CODE" value="{{ $postCode }}">
                    <input type="hidden" name="POST_CODE" value="{{ $delivery_data['POST_CODE'] ?? '' }}">
                    <label class="checkout-label">都道府県</label>
                    <input type="text" class="checkout-input-sub" value="{{ $delivery_data['PREFECTURE_NAME'] ?? '' }}" readonly>
                </div>
                <div class="checkout-row">
                    <label class="checkout-label">住所1</label>
                    <input type="text" class="checkout-input" value="{{ $delivery_data['ADDRESS1'] ?? '' }}" readonly>
                    <input type="hidden" name="ADDRESS1" value="{{ $delivery_data['ADDRESS1'] ?? '' }}">
                </div>
                <div class="checkout-row">
                    <label class="checkout-label">住所2</label>
                    <input type="text" class="checkout-input" value="{{ $delivery_data['ADDRESS2'] ?? '' }}" readonly>
                    <input type="hidden" name="ADDRESS2" value="{{ $delivery_data['ADDRESS2'] ?? '' }}">
                </div>
                <div class="checkout-row">
                    <label class="checkout-label">住所3</label>
                    <input type="text" class="checkout-input" value="{{ $delivery_data['ADDRESS3'] ?? '' }}" readonly>
                    <input type="hidden" name="ADDRESS3" value="{{ $delivery_data['ADDRESS3'] ?? '' }}">
                </div>
                <div class="checkout-row">
                    <label class="checkout-label">電話番号</label>
                    <input type="text" class="checkout-input" value="{{ $delivery_data['TEL'] ?? '' }}" readonly>
                    <input type="hidden" name="TEL" value="{{ $delivery_data['TEL'] ?? '' }}"> <label class="checkout-label">携帯電話番号</label>
                    <input type="text" class="checkout-input" value="{{ $delivery_data['MOBILE_TEL'] ?? '' }}" readonly>
                </div>
                <div class="checkout-row">
                    <label class="checkout-label">メールアドレス</label>
                    <input type="text" class="checkout-input" value="{{ $delivery_data['EMAIL'] ?? '' }}" readonly>
                    <input type="hidden" name="EMAIL" value="{{ $delivery_data['EMAIL'] ?? '' }}">
                    <label class="checkout-label">携帯メール</label>
                    <input type="text" class="checkout-input" value="{{ $delivery_data['MOBILE_EMAIL'] ?? '' }}" readonly>
                </div>
                <div class="checkout-row">
                    <label class="checkout-label">会社名</label>
                    <input type="text" class="checkout-input" value="マルホ株式会社" readonly>
                    <label class="checkout-label">部署名</label>
                    <input type="text" class="checkout-input" value="{{ $soshiki1->SOSHIKI1_NAME ?? '' }} {{ $soshiki2->SOSHIKI2_NAME ?? '' }}" readonly>
                </div>
                <div class="checkout-row">
                    <label class="checkout-label">備考</label>
                    <textarea class="checkout-textarea" name="NOTE" style="background-color: white;">{{ old('NOTE', $delivery_data['NOTE'] ?? '') }}</textarea>
                </div>
            </div>
            <div class="checkout-actions" style="margin-top: 40px; display: flex;">
                <button type="button" class="checkout-btn" style="background-color: black; width: 140px;" onclick="location.href='{{ route('carts.index') }}'">戻る</button>
                <form method="POST" action="{{ route('carts.checkout') }}" style="display:inline;">
                    @csrf
                    <div class="checkout-buttons" style="margin: auto;">
                    <button type="submit" name="reset" value="1" class="checkout-btn" style="height: 100%; width: 200px; background-color: #EEEEEE; border: 1px solid black; color: black;">リセット</button>
                </form>
                <button type="submit" formaction="{{ route('carts.confirm') }}" class="checkout-btn checkout-btn-main" style="text-decoration: none; width: 210px; background: #007bff;">注文内容確認へ</button>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection