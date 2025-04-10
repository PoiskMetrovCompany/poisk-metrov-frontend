if (window.location.hash) {
    const linkParse = window.location.hash.substring(1);
    const anchors = [
    'apartment_type_study',
    'apartment_type_one',
    'apartment_type_two',
    'apartment_type_free',
    'apartment_type_foo',
    'apartment_type_five',
    'apartment_type_six',
    ];

    document.querySelector('.anchor').setAttribute('id', linkParse);

    let anchorIndex = null;
    for (let i = 0; i < anchors.length; i++)
    {
        if (anchors[i] === linkParse) {
            anchorIndex = i;
            break;
        }
    }
    document.getElementById(`apartment-dropdown-${[anchorIndex]}`).classList.add('open');
}
