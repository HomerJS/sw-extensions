import '../component/swag-first-monday';

Shopware.Application.addServiceProviderDecorator('ruleConditionDataProviderService', (ruleConditionService) => {

    const restrictions = ruleConditionService.getAwarenessConfigurationByAssignmentName('productPrices');

    ruleConditionService.addAwarenessConfiguration('productPrices', {
            notEquals: [
                'first_monday'
            ],
            equalsAny: [ ], // ignore if not needed
            snippet: 'sw-restricted-rules.restrictedAssignment.productPrices',
        });

    ruleConditionService.upsertGroup('days_of_the_month', {
        id: 'days_of_the_month',
        name: 'Days of the month',
    });

    ruleConditionService.addCondition('first_monday', {
        component: 'swag-first-monday',
        label: 'Is first monday of the month',
        scopes: ['global'],
        group: 'days_of_the_month',
    });



    return ruleConditionService;
});