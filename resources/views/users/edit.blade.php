@extends('layouts.app')

@section('content')
<div class="user-edit-wrapper">
    <div class="user-edit-card">
        <h2 class="user-edit-title">ユーザー登録情報</h2>

        <form id="passwordForm">
            @csrf
            <table class="user-edit-table">
                <tr><th>氏名</th><td><input type="text" value="{{ $user->NAME }}" readonly></td></tr>
                <tr><th>パスワード</th><td><input type="password" name="PASSWORD" required></td></tr>
                <tr><th>郵便番号</th><td><input type="text" value="{{ $formattedPostCode }}" readonly></td></tr>
                <tr><th>都道府県</th><td><input type="text" value="{{ $prefectureName }}" readonly></td></tr>
                <tr><th>住所1</th><td><input type="text" value="{{ $user->ADDRESS1 }}" readonly></td></tr>
                <tr><th>住所2</th><td><input type="text" value="{{ $user->ADDRESS2 }}" readonly></td></tr>
                <tr><th>住所3</th><td><input type="text" value="{{ $user->ADDRESS3 }}" readonly></td></tr>
                <tr><th>電話番号</th><td><input type="text" value="{{ $user->TEL }}" readonly></td></tr>
                <tr><th>携帯電話番号</th><td><input type="text" value="{{ $user->MOBILE_TEL }}" readonly></td></tr>
                <tr><th>メールアドレス</th><td><input type="text" value="{{ $user->EMAIL }}" readonly></td></tr>
                <tr><th>携帯メール</th><td><input type="text" value="{{ $user->MOBILE_EMAIL }}" readonly></td></tr>
                <tr>
                    <th>部署名</th>
                    <td>
                        <input type="text" value="{{ $user->soshiki1->SOSHIKI1_NAME ?? '' }} {{ $user->soshiki2->SOSHIKI2_NAME ?? '' }}" readonly>
                    </td>
                </tr>
            </table>

            <div class="user-edit-actions">
                <button type="button" onclick="showModal()" class="user-edit-btn">確認する</button>
            </div>
        </form>
    </div>
</div>

<!-- モーダル -->
<div id="confirmModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:white; padding:2rem; margin:10% auto; width:90%; max-width:400px; text-align:center;">
        <p>パスワードを変更してよろしいですか？</p>
        <button onclick="submitForm()" class="user-edit-btn">OK</button>
        <button onclick="hideModal()" class="user-edit-cancel">キャンセル</button>
    </div>
</div>

<script>
    function showModal() {
        document.getElementById('confirmModal').style.display = 'block';
    }
    function hideModal() {
        document.getElementById('confirmModal').style.display = 'none';
    }
    function submitForm() {
        const form = document.getElementById('passwordForm');
        const formData = new FormData(form);

        fetch("{{ route('users.complete') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('[name=_token]').value
            },
            body: formData
        })
        .then(res => {
            if (res.redirected) {
                window.location.href = res.url;
            } else {
                return res.text().then(html => {
                    document.body.innerHTML = html;
                });
            }
        });
    }
</script>
@endsection
