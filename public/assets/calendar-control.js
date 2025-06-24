console.log("flatpickr 読込開始");

window.addEventListener('DOMContentLoaded', function () {
    // 任意の class にだけ適用したいなら .flatpickr クラスだけに限定可能
    flatpickr("input[type='text'].date-input", {
        dateFormat: "Y/m/d",
        locale: "ja",
        allowInput: true,
    });

    console.log("flatpickr 初期化完了");
});
