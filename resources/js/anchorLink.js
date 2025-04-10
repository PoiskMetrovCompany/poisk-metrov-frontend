if (window.location.hash) {
    const linkParse = window.location.hash.substring(1);
    const anchors = [
        ['apartment_type_study', 'Студия'],
        ['apartment_type_one', '1-комнатные'],
        ['apartment_type_two', '2-комнатные'],
        ['apartment_type_free', '3-комнатные'],
        ['apartment_type_foo', '4-комнатные'],
        ['apartment_type_five', '5-комнатные'],
        ['apartment_type_six', '6-комнатные'],
    ];

    document.querySelector('.anchor').setAttribute('id', linkParse);

    let anchorIndex = null;
    for (let i = 0; i < anchors.length; i++)
    {
        if (anchors[i][0] === linkParse) {
            anchorIndex = i;

            const dropdownHeaderList = document.querySelectorAll('#type_apartment');
            dropdownHeaderList.forEach((el) => {
                el.querySelector('#type_apartment');

                if (el.innerHTML.trim() === anchors[i][1]) {
                    document.querySelector(`#apartment-dropdown-${[anchorIndex]}`).classList.add('open');
                }
            });

            break;
        }
    }
}
