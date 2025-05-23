<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Mops Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/manage.css') }}">
    <script src="https://kit.fontawesome.com/c77ed6d11a.js" crossorigin="anonymous"></script>
</head>
@extends('layouts.manage')

@section('content')
    <!-- タブボタンボックス -->
    <div class="tab-wrapper">
        <div class="tab-container">
            <a href="{{ route('managementuser.index') }}" class="tab-button {{ request()->routeIs('managementuser.index') ? 'active' : '' }}">検索・一覧</a>
            <a href="{{ route('managementuser.create') }}" class="tab-button {{ request()->routeIs('managementuser.create') ? 'active' : '' }}">新規</a>
            <a href="{{ route('managementuser.import') }}" class="tab-button {{ request()->routeIs('managementuser.import') ? 'active' : '' }}">インポート</a>
            <a href="{{ route('managementuser.export') }}" class="tab-button {{ request()->routeIs('managementuser.export') ? 'active' : '' }}">エクスポート</a>
        </div>
    </div>
    <!-- 検索フォーム -->
    <div class="search-box">
        <form method="GET" action="{{ route('managementuser.index') }}">
            <div class="form-row">
                <input type="text" name="keyword" placeholder="キーワード" class="text-input">

                <label><input type="checkbox" name="search_target[]" value="user_id"> 社員ID</label>
                <label><input type="checkbox" name="search_target[]" value="user_name"> 氏名</label>
                <label><input type="checkbox" name="search_target[]" value="user_kana"> 氏名カナ</label>
            </div>

            <div class="form-row">
                <label>支店・部：</label>
                <select name="branch" class="select-input">
                    <option value="">選択してください</option>
                    <option value="tokyo">東京支店</option>
                    <option value="osaka">大阪支店</option>
                </select>

                <label>営業所・グループ：</label>
                <select name="office" class="select-input">
                    <option value="">選択してください</option>
                    <option value="group_a">グループA</option>
                    <option value="group_b">グループB</option>
                </select>

                <label><input type="checkbox" name="resident" value="1"> 駐在員</label>
            </div>

            <div class="form-row btn-row">
                <button type="reset" class="btn-clear">検索条件をクリア</button>
                <button type="submit" class="submit">検索する</button>
            </div>
        </form>
    </div>
    <!-- ユーザー一覧 -->
    <div class="user-section" style="width: 100%; margin: 0 auto;">

        <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>社員ID</th>
                    <th>氏名</th>
                    <th>氏名カナ</th>
                    <th>メールアドレス</th>
                    <th>支店・部</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <input type="text" value="{{ $user['USER_ID'] ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                        </td>
                        <td>
                            <input type="text" value="{{ $user['USER_NAME'] ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                        </td>
                        <td>
                            <input type="text" value="{{ $user['NAME_KANA'] ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                        </td>
                        <td>
                            <input type="text" value="{{ $user['EMAIL'] ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                        </td>
                        <td style="display: flex; gap: 6px; align-items: center;">
                            <input type="text" value="{{ $user['SHITEN_BU_CODE'] ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">

                            <a href="{{ route('managementuser.detail', ['id' => $user['USER_ID']]) }}" class="btn-detail" style="padding: 4px 8px; background: #007bff; color: #fff; border-radius: 4px; text-decoration: none;">詳細</a>

                            <form action="{{ route('managementuser.delete', ['id' => $user['USER_ID']]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" style="padding: 4px 8px; background: #dc3545; color: #fff; border: none; border-radius: 4px;">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@endsection

