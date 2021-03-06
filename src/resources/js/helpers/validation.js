const REQUIRE = 'required';
const STRING = 'string';
const CONTENT = 'content';
const URL = 'url';
const EMAIL = 'email';
const INTEGER = 'integer';
const NULLABLE = 'nullable';

export function validate(name, value, params) {
    let pattern;
    let error = '';
    let errors = [];

    if (params.includes(NULLABLE)) {
        return null;
    }

    params.forEach(param => {
        switch (param) {
            case REQUIRE:
                pattern = /^.{2,}$/;
                error = 'Помилка зі значенням %value%, мінімально 6 символів.';
                break;
            case STRING:
                pattern = /^[a-zA-Zа-яА-Я]+$/;
                error = 'Помилка зі значенням %value%, повинно бути строкою.';
                break;
            case CONTENT:
                pattern = /.*/;
                error = 'Помилка зі значенням %value%, повинно бути контентом.';
                break;
            case URL:
                pattern = /^[a-zA-Z0-9_-]+$/;
                error = 'Помилка зі значенням %value%, повинно бути url\'ом.';
                break;
            case INTEGER:
                pattern = /^\d+$/;
                error = 'Помилка зі значенням %value%, повинно бути номером.';
                break;
            case EMAIL:
                pattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                error = 'Помилка зі значенням %value%, повинно бути номером email\'oм.';
                break;
            default:
                pattern = /^.{6,}$/;
                break;
        }

        if (!pattern.test(value)) {
            error = error.replace('%name%', name);
            error = error.replace('%value%', value);

            errors.push(error)
        }
    });

    return errors.length > 1 ? errors[errors.length - 1] : errors[0];
}
