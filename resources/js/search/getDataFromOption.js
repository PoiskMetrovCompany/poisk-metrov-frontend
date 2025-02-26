export function getDataFromOption(option) {
    const data = {};

    data.value = option.getAttribute('value');
    data.field = option.getAttribute('field');
    data.displayName = option.getAttribute('displayName');
    data.condition = option.getAttribute('condition');
    data.searchid = option.getAttribute('searchid');
    data.context = option.getAttribute('context');
    data.secondaryField = option.getAttribute('secondaryfield');
    data.secondaryCondition = option.getAttribute('secondarycondition');
    data.secondaryValue = option.getAttribute('secondaryvalue');

    return data;
}