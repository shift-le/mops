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

<style>
/* manage.css */
.pagination-wrapper svg {
    width: 20px !important;
    height: 20px !important;
}

.pagination-wrapper .flex {
    justify-content: center;
}

.pagination-wrapper .page-link {
    padding: 4px 8px;
    margin: 0 2px;
    border: 1px solid #ccc;
    border-radius: 4px;
    color: #007bff;
    text-decoration: none;
}

.pagination-wrapper .page-link:hover {
    background-color: #f0f0f0;
}

.pagination-wrapper .active .page-link {
    font-weight: bold;
    background-color: #007bff;
    color: #fff;
}

</style>
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
                <input type="text" name="user" value="{{ request('user') }}" placeholder="キーワード" class="text-input">
                <label><input type="checkbox" name="search_target[]" value="USER_ID" {{ in_array('USER_ID', request()->input('search_target', [])) ? 'checked' : '' }}>
                社員ID
                </label>
                <label><input type="checkbox" name="search_target[]" value="NAME" {{ in_array('NAME', request()->input('search_target', [])) ? 'checked' : '' }}>
                氏名
                <label><input type="checkbox" name="search_target[]" value="NAME_KANA" {{ in_array('NAME_KANA', request()->input('search_target', [])) ? 'checked' : '' }}>
                氏名カナ
                </label>
            </div>

            <div class="form-row">
                <select name="branch" class="select-input">
                    <option value="">支店・部</option>
                    @foreach ($branchList as $code => $name)
                        <option value="{{ $code }}" {{ request('branch') == $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>

                <select name="office" class="select-input">
                    <option value="">営業所・グループ</option>
                    @foreach ($officeList as $code => $name)
                        <option value="{{ $code }}" {{ request('office') == $code ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>

                <input type="checkbox" name="resident" value="1" {{ request('resident') ? 'checked' : '' }}> 駐在員
            </div>

            <hr>

            <div class="form-row btn-row">
                <a href="{{ route('managementuser.index') }}" class="btn-clear" style="padding: 6px 12px; background: #6c757d; color: #6c757d; border-radius: 4px; text-decoration: none; background-color:#fff;">検索条件をクリア</a>
                <button type="submit" class="submit">検索する</button>
            </div>
        </form>
    </div>
    <!-- ユーザー一覧 -->
    <div class="user-section" style="width: 100%; margin: 0 auto;">

        <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <thead style="background-color:rgb(82, 83, 85);">
                <tr style="color:#fff;"> 
                    <th>社員ID</th>
                    <th>氏名</th>
                    <th>氏名カナ</th>
                    <th>支店・部</th>
                    <th>駐在員</th>
                    <th></th>
                </tr>
            </thead>
            <tbody style="background-color:#fff;">
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <input type="text" value="{{ $user->USER_ID ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                        </td>
                        <td>
                            <input type="text" value="{{ $user->NAME ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                        </td>
                        <td>
                            <input type="text" value="{{ $user->NAME_KANA ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                        </td>
                        <td>
                            <input type="text" value="{{ $branchList[$user->SHITEN_BU_CODE] ?? $user->SHITEN_BU_CODE }}" readonly style="width: 100%; border: none; background: transparent;">
                        </td>
                        <td>
                            @if (in_array($user->USER_ID, $residentIds))
                                駐在員
                            @endif
                        </td>
                        <td style="display: flex; gap: 6px; align-items: center;">
                            <a href="{{ route('managementuser.detail', ['id' => $user->USER_ID]) }}" class="btn-detail" style="padding: 4px 8px; background: #fff; color: #007bff; border-radius: 4px;">詳細</a>

                            <form action="{{ route('managementuser.delete', ['id' => $user->USER_ID]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                <button type="submit" class="btn-delete" style="padding: 4px 8px; background: #fff; color: #dc3545; border-radius: 4px;">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination-wrapper" style="margin-top: 20px; text-align: center;">
            {{ $users->links('components.numeric') }}
        </div>  
    </div>

@endsection

