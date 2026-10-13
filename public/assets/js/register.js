document.getElementById('togglePassword').addEventListener('click', function () {
    const input = document.getElementById('password');
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    document.getElementById('iconEye').style.display    = isHidden ? 'none' : '';
    document.getElementById('iconEyeOff').style.display = isHidden ? ''     : 'none';
});

document.getElementById('togglePassword2').addEventListener('click', function () {
    const input = document.getElementById('confirm_password');
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    document.getElementById('iconEye2').style.display    = isHidden ? 'none' : '';
    document.getElementById('iconEyeOff2').style.display = isHidden ? ''     : 'none';
});
