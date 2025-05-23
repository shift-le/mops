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

    <!-- 新規登録フォーム -->
     <h2>ユーザ情報 新規登録 入力</h2>
    <div class="content-box">
        <h3>基本情報</h3>

        <form method="POST" action="{{ route('managementuser.create') }}">
            @csrf
            <div class="form-row">
                <label>社員ID：</label>
                <input type="text" name="user_id" class="text-input" required>
            </div>

            <div class="form-row">
                <label>氏名：</label>
                <input type="text" name="user_name" class="text-input" required>
            </div>

            
            <div class="form-row">
                <label>氏名カナ：</label>
                <input type="text" name="email" class="text-input" required>
            </div>

            <div class="form-row">
                <label>メールアドレス：</label>
                <input type="email" name="email" class="text-input" required>
            </div>
            
            <div class="form-row">
                <label>携帯電話番号：</label>
                <input type="phone" name="phone" class="text-input" required>
            </div>
            
            <div class="form-row">
                <label>携帯メールアドレス：</label>
                <input type="phone-email" name="phone-email" class="text-input" required>
            </div>

            <div class="form-row">
                <label>所属部署：</label>
                <input type="text" name="department" class="text-input">
            </div>
            
            <div class="form-row">
                <label>所属：</label>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <select name="branch_department" class="text-input">
                        <option value="">支店・部を選択</option>
                        <option value="東京支店">東京支店</option>
                        <option value="大阪支店">大阪支店</option>
                        <option value="営業部">営業部</option>
                    </select>

                    <select name="office_group" class="text-input">
                        <option value="">営業所・グループを選択</option>
                        <option value="第1営業所">第1営業所</option>
                        <option value="第2営業所">第2営業所</option>
                        <option value="開発グループ">開発グループ</option>
                    </select>
                </div>
            </div>


            <div class=>            
            <div class="form-row btn-row">
                <button type="submit" class="submit">登録する</button>
            </div>
        </form>
    </div>
@endsection
