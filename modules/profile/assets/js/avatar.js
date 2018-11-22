(function (Croppr) {

    const reader = new FileReader();
    const avatarImage = document.getElementById('rls-avatar-image');

    const avatarInput = document.createElement('input');
    avatarInput.setAttribute('type', 'file');
    avatarInput.setAttribute('name', 'file');

    document.querySelectorAll('[rls-avatar-changer]').forEach(elm => {
        elm.addEventListener('click', e => {
            avatarInput.value = "";
            avatarInput.click();
        });
    });

    var cropInstance = null;

    reader.onload = e => {
        $('#rls-avatar-modal').modal('show');

        if (cropInstance) {
            cropInstance.destroy();
        }

        avatarImage.src = e.target.result;

        cropInstance = new Croppr('#rls-avatar-image', {
            // ...options
        });
    };

    avatarInput.addEventListener('change', e => {
        if (e.target.files.length == 0) return;

        const f = e.target.files[0];
        reader.readAsDataURL(f);
    });

}) (Croppr);