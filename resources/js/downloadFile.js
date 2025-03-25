export function downloadFile(url, fileName) {
  fetch(url, { method: 'get' })
    .then(res => res.blob())
    .then(res => {
      const aElement = document.createElement('a');
      aElement.setAttribute('download', fileName);
      const href = URL.createObjectURL(res);
        console.log(href)
      aElement.href = href;
      aElement.setAttribute('target', '_blank');
      aElement.click();
      console.log(aElement)
      URL.revokeObjectURL(href);
    });
};
