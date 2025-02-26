export function getHiddenItems(scrollElement) {
	const htmlCollection = [...scrollElement.children];
	return getHiddenItemsWithCollection(scrollElement, htmlCollection);
}

export function getHiddenItemsWithCollection(scrollElement, htmlCollection) {
	const gap = Number.parseInt(getComputedStyle(scrollElement).gap);
	let hiddenItems = [];
	let occupiedWidth = 0;
	htmlCollection.forEach((element) => {
		occupiedWidth += element.clientWidth + gap;
		if (scrollElement.scrollLeft > (occupiedWidth - gap) && !hiddenItems.includes(element)) {
			hiddenItems.push(element);
		}
	});
	return hiddenItems;
}