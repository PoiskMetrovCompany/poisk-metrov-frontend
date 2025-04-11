if (window.location.hash) {

    const linkParse = decodeURIComponent(window.location.hash.substring(1));
    const dropdownHeaders = document.querySelectorAll('#type_apartment');
    const anchors = Array.from(dropdownHeaders).map(el => el.innerHTML.trim());

    document.querySelector('.anchor').setAttribute('id', linkParse);
    let anchorIndex = null;
    for (let i = 0; i < anchors.length; i++)
    {
        if (anchors[i] === linkParse) {
            anchorIndex = i;

            const dropdownHeaderList = document.querySelectorAll('#type_apartment');
            dropdownHeaderList.forEach((el) => {
                el.querySelector('#type_apartment');

                if (el.innerHTML.trim() === anchors[i]) {
                    try {
                        document.querySelector(`#apartment-dropdown-${[anchorIndex]}`).classList.add('open');
                    } catch (TypeError) {}

                }
            });
        }
    }
}
