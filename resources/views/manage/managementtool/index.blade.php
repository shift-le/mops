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
            <a href="{{ route('managementtool.index') }}" class="tab-button {{ request()->routeIs('managementtool.index') ? 'active' : '' }}">検索・一覧</a>
            <a href="{{ route('managementtool.create') }}" class="tab-button {{ request()->routeIs('managementtool.create') ? 'active' : '' }}">新規</a>
            <a href="{{ route('managementtool.import') }}" class="tab-button {{ request()->routeIs('managementtool.import') ? 'active' : '' }}">インポート</a>
        </div>
    </div>
    <!-- 検索フォーム -->
    <div class="search-box">
        <form method="GET" action="{{ route('managementtool.index') }}">
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


            <div class="form-row btn-row">
                <a href="{{ route('managementtool.index') }}" class="btn-clear" style="padding: 6px 12px; background: #6c757d; color: #fff; border-radius: 4px; text-decoration: none;">検索条件をクリア</a>
                <button type="submit" class="submit">検索する</button>
            </div>
        </form>
    </div>
    <!-- ユーザー一覧 -->
    <div class="user-section" style="width: 100%; margin: 0 auto;">

        <table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>ツール名</th>
                    <th>ツールコード</th>
                    <th>ステータス</th>
                    <th>メールアドレス</th>
                    <th>支店・部</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tools as $tool)
                    <tr>
                        <td>
                            <input type="text" value="{{ $tool->TOOL_CODE ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                        </td>
                        <td>
                            <input type="text" value="{{ $tool->TOOL_NAME ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                        </td>
                        <td>
                            <input type="text" value="{{ $tool->NAME_KANA ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                        </td>
                        <td>
                            <input type="text" value="{{ $tool->RYOIKI ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                        </td>
                        <td style="display: flex; gap: 6px; align-items: center;">
                            <input type="text" value="{{ $tool->SHITEN_BU_CODE ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">

                            <a href="{{ route('managementtool.detail', ['id' => $tool->USER_ID]) }}" class="btn-detail" style="padding: 4px 8px; background: #007bff; color: #fff; border-radius: 4px; text-decoration: none;">詳細</a>

                            <form action="{{ route('managementtool.delete', ['id' => $tool->USER_ID]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
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

