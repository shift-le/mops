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
                <input type="text" name="tool" value="{{ request('tool') }}" placeholder="キーワード" class="text-input">
                <label><input type="checkbox" name="search_target[]" value="TOOL_CODE" {{ in_array('TOOL_CODE', request()->input('search_target', [])) ? 'checked' : '' }}>
                ツールコード
                </label>
                <label><input type="checkbox" name="search_target[]" value="TOOL_NAME" {{ in_array('TOOL_NAME', request()->input('search_target', [])) ? 'checked' : '' }}>
                ツール名
                <label><input type="checkbox" name="search_target[]" value="TOOL_NAME_KANA" {{ in_array('TOOL_NAME_KANA', request()->input('search_target', [])) ? 'checked' : '' }}>
                ツール名カナ
                </label>
            </div>
            <div class="form-row">
                <select name="RYOIKI" class="select-input">
                    <option value="">領域</option>
                    @foreach($ryoikis as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                <select name="HINMEI" class="select-input">
                    <option value="">品名</option>
                    @foreach($hinmeis as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                <select name="SHITEN_BU_CODE" class="select-input">
                    <option value="">支店・部</option>
                    @foreach($branches as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>

            </div>
            <div class="form-row">
                <label>ステータス</label>
                <label><input type="radio" name="TOOL_STATUS" value="0" checked> 表示</label>
                <label><input type="radio" name="TOOL_STATUS" value="1"> 仮登録</label>
                <label><input type="radio" name="TOOL_STATUS" value="2"> マルホ確認済み</label>
                <label><input type="radio" name="TOOL_STATUS" value="3"> 中島準備完了</label>
                <label><input type="radio" name="TOOL_STATUS" value="4"> 非表示</label>
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
                    <th>
                        <input type="checkbox" name="select_all" id="select_all" style="width: 20px; height: 20px;">
                    </th>
                    <th>ツール名</th>
                    <th>ツールコード</th>
                    <th>ステータス</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tools as $tool)
                    <tr>
                        <td>
                            <input type="checkbox" name="select_tool[]" value="{{ $tool->TOOL_CODE }}" class="select-tool" style="width: 20px; height: 20px;">
                        </td>
                        <td>
                            <input type="text" value="{{ $tool->TOOL_NAME ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                        </td>
                        <td>
                            <input type="text" value="{{ $tool->TOOL_CODE ?? '' }}" readonly style="width: 100%; border: none; background: transparent;">
                        </td>
                        @php
                            $statusLabels = [
                                0 => '表示',
                                1 => '仮登録',
                                2 => 'マルホ確認済み',
                                3 => '中島準備完了',
                                4 => '非表示',
                            ];
                        @endphp
                        <td>
                            <input type="text" value="{{ $statusLabels[$tool->TOOL_STATUS] ?? '不明' }}" readonly style="width: 100%; border: none; background: transparent;">
                        </td>
                        <td style="display: flex; gap: 6px; align-items: right;">
                            <a href="{{ route('managementtool.detail', ['id' => $tool->TOOL_CODE]) }}" class="btn-detail" style="padding: 4px 8px; background: #007bff; color: #fff; border-radius: 4px; text-decoration: none;">詳細</a>

                            <form action="{{ route('managementtool.delete', ['id' => $tool->TOOL_CODE]) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select_all');
        const checkboxes = document.querySelectorAll('.tool_checkbox');

        selectAll.addEventListener('change', function () {
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        });
    });
</script>
