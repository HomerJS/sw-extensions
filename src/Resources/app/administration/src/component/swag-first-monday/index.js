import template from './swag-first-monday.html.twig';

Shopware.Component.extend('swag-first-monday', 'sw-condition-base', {
    template,

    computed: {
        selectValues() {
            return [
                {
                    label: this.$tc('global.sw-condition.condition.yes'),
                    value: true
                },
                {
                    label: this.$tc('global.sw-condition.condition.no'),
                    value: false
                }
            ];
        },

        isFirstMondayOfTheMonth: {
            get() {
                this.ensureValueExist();

                if (this.condition.value.isFirstMondayOfTheMonth == null) {
                    this.condition.value.isFirstMondayOfTheMonth = false;
                }

                return this.condition.value.isFirstMondayOfTheMonth;
            },
            set(isFirstMondayOfTheMonth) {
                this.ensureValueExist();
                this.condition.value = { ...this.condition.value, isFirstMondayOfTheMonth };
            }
        }
    }
});