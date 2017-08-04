// Validation errors messages for Parsley
import Parsley from '../parsley';

Parsley.addMessages('ua', {
    defaultMessage: "Некоректне значення.",
    type: {
        email: "Введіть адресу електронної пошти.",
        url: "Введіть URL адресу.",
        number: "Введіть число.",
        integer: "Введіть ціле число.",
        digits: "Введіть тільки цифри.",
        alphanum: "Введіть буквено-цифрове значення."
    },
    notblank: "Це поле має бути заповненим.",
    required: "Обов'язкове поле.",
    pattern: "Це значення некоректне.",
    min: "Це значення повинно бути не менше ніж %s.",
    max: "Це значення повинно бути не більше ніж %s.",
    range: "Це значення повинно бути від %s до %s.",
    minlength: "Це значення повинно містити не менше %s символів.",
    maxlength: "Це значення повинно містити не більше %s символів.",
    length: "Це значення повинно містити від %s до %s символів.",
    mincheck: "Виберіть не менше %s значень.",
    maxcheck: "Виберіть не більше %s значень.",
    check: "Виберіть від %s до %s значень.",
    equalto: "Це значення повинно співпадати."
});

Parsley.setLocale('ua');
