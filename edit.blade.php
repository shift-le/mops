@extends('layouts.app')

@section('content')
<div class="user-edit-wrapper">
    <div class="user-edit-card">
        <h2 class="user-edit-title">ユーザー登録情報</h2>
        <p style="margin-top: 40px;">ユーザー登録情報</p>
                @if ($errors->has('PASSWORD'))
            <div style="color: red; font-size: small;">
                {{ $errors->first('PASSWORD') }}
            </div>
        @endif


        <form id="passwordForm">
            @csrf
            <table class="user-edit-table">
                <tr>
                    <th>氏名</th>
                    <td colspan="3"><input type="text" value="{{ $user->NAME }}" style="border: none; background-color: white;" readonly></td>
                </tr>
<tr>
    <th>パスワード</th>
    <td colspan="3">
        <input type="password" name="PASSWORD" style="background-color: white; border: 1px solid #ccc;" required>
    </td>
</tr>
                <tr>
                    <th>郵便番号</th>
                    <td><input type="text" value="{{ $formattedPostCode }}" readonly style="width: auto;"></td>
                    <th>都道府県</th>
                    <td><input type="text" value="{{ $prefectureName }}" readonly style="width: auto;"></td>
                </tr>
                <tr>
                    <th>住所漢字1</th>
                    <td colspan="3"><input type="text" value="{{ $user->ADDRESS1 }}" readonly style="width: 785px;"></td>
                </tr>
                <tr>
                    <th>住所漢字2</th>
                    <td colspan="3"><input type="text" value="{{ $user->ADDRESS2 }}" readonly style="width: 785px;"></td>
                </tr>
                <tr>
                    <th>住所漢字3</th>
                    <td colspan="3"><input type="text" value="{{ $user->ADDRESS3 }}" readonly style="width: 785px;"></td>
                </tr>
                <tr>
                    <th>電話番号</th>
                    <td><input type="text" value="{{ $user->TEL }}" readonly></td>
                    <th>携帯電話番号</th>
                    <td><input type="text" value="{{ $user->MOBILE_TEL }}" readonly></td>
                </tr>
                <tr>
                    <th>メールアドレス</th>
                    <td><input type="text" value="{{ $user->EMAIL }}" readonly></td>
                    <th>携帯メール</th>
                    <td><input type="text" value="{{ $user->MOBILE_EMAIL }}" readonly></td>
                </tr>
                <tr>
                    <th>会社名</th>
                    <td><input type="text" value="マルホ株式会社" readonly></td>
                    <th>部署名</th>
                    <td>
                        <input type="text" value="{{ $user->soshiki1->SOSHIKI1_NAME ?? '' }} {{ $user->soshiki2->SOSHIKI2_NAME ?? '' }}" readonly>
                    </td>
                </tr>
            </table>

<div class="user-edit-actions" style="display: flex; justify-content: center; gap: 15px; margin-top: 30px;">
    <button type="button" onclick="resetForm()" class="user-edit-cancel" style="height: 35px; width: 170px; background-color: white; border: 1px solid #ccc;">リセット</button>
    <button type="button" onclick="showModal()" class="user-edit-btn" style="height: 35px; width: 170px; background-color: #0099FF; color: white; border: 1px solid #ccc;">確認する</button>
</div>
        </form>
    </div>
</div>

<!-- モーダル -->
<div id="confirmModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:white; padding:2rem; margin:10% auto; width:90%; max-width:400px; text-align:center; border: 2px solid #0099FF;">
        <p style="color: #0099FF;">パスワードを変更してよろしいですか？</p>
        <div class="user-edit-modal-actions" style="display: flex; justify-content: center; gap: 15px; margin-top: 1.5rem;">
            <button onclick="hideModal()" class="user-edit-cancel" style="height: 35px; width: 130px; background-color: white; border: 1px solid #ccc;">キャンセル</button>
            <button onclick="submitForm()" class="user-edit-btn" style="height: 35px; width: 130px; background-color: #0099FF; border: 1px solid #ccc; color: white;">OK</button>
        </div>
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

    function resetForm() {
        document.getElementById('passwordForm').reset();
    }
</script>
@endsection