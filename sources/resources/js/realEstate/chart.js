import Chart from 'chart.js/auto';
import { openMenu } from '../menu';

export function loadChart(layout) {
  let chartId = layout.querySelector('#chartId');
  if (chartId) {
    chartId = chartId.value;
    const toChartButton = layout.querySelector('#to-price-chart-' + chartId);

    if (toChartButton) {
      toChartButton.addEventListener('click', () => {
        openMenu('price-chart-modal-' + chartId);
        const data = JSON.parse(document.getElementById('history-data-' + chartId).value);
        showChart(data, chartId);
      });
    }
  }
}

async function showChart(data, code) {
  if (data.length === 1) {
    data.push({ price: data[0].price, date: '' });
  }

  const canvas = document.getElementById('price-chart-' + code);
  const context = canvas.getContext('2d');
  const gradient = context.createLinearGradient(0, 0, 0, canvas.width);
  gradient.addColorStop(0, 'rgba(236, 125, 63, 0.5)');
  gradient.addColorStop(0.5, 'rgba(255, 255, 255, 0.6)');
  gradient.addColorStop(1, 'rgba(255, 255, 255, 1)');
  const max = Math.max(data.map(row => row.data));
  // const min = Math.min(data.map(row => row.data));
  const min = 0;

  const getOrCreateTooltip = (chart) => {
    let tooltipEl = chart.canvas.parentNode.querySelector('div');

    if (!tooltipEl) {
      tooltipEl = document.createElement('div');
      tooltipEl.style.background = 'rgba(255, 255, 255, 1)';
      tooltipEl.style.borderRadius = '8px';
      tooltipEl.style.color = 'rgba(24, 24, 23, 1)';
      tooltipEl.style.fontSize = '12px';
      tooltipEl.style.fontWeight = 500;
      tooltipEl.style.opacity = 1;
      tooltipEl.style.pointerEvents = 'none';
      tooltipEl.style.position = 'absolute';
      tooltipEl.style.transform = 'translate(-50%, 0)';
      tooltipEl.style.transition = 'all .1s ease';
      tooltipEl.style.padding = '8px 16px';
      tooltipEl.style.boxShadow = '2px 2px 6px rgba(0, 0, 0, 0.15)';
      tooltipEl.style.width = 'max-content';
      tooltipEl.style.textAlign = 'center'

      const table = document.createElement('table');
      table.style.margin = '0px';

      tooltipEl.appendChild(table);
      chart.canvas.parentNode.appendChild(tooltipEl);
    }

    return tooltipEl;
  };

  const externalTooltipHandler = (context) => {
    const { chart, tooltip } = context;
    const tooltipEl = getOrCreateTooltip(chart);

    if (tooltip.opacity === 0) {
      tooltipEl.style.opacity = 0;
      return;
    }

    if (tooltip.body) {
      const titleLines = tooltip.title || [];
      const bodyLines = tooltip.body.map(b => b.lines);

      const tableHead = document.createElement('thead');

      titleLines.forEach(title => {
        const tr = document.createElement('tr');
        tr.style.borderWidth = 0;

        const th = document.createElement('th');
        th.style.borderWidth = 0;
        th.style.fontWeight = 400;
        const text = document.createTextNode(title);

        th.appendChild(text);
        tr.appendChild(th);
        tableHead.appendChild(tr);
      });

      const tableBody = document.createElement('tbody');
      bodyLines.forEach((body, i) => {

        const tr = document.createElement('tr');
        tr.style.backgroundColor = 'inherit';
        tr.style.borderWidth = 0;

        const td = document.createElement('td');
        td.style.borderWidth = 0;
        td.style.fontWeight = 500;
        td.style.fontSize = '14px';
        const text = document.createTextNode(body);

        td.appendChild(text);
        tr.appendChild(td);
        tableBody.appendChild(tr);
      });

      const tableRoot = tooltipEl.querySelector('table');

      while (tableRoot.firstChild) {
        tableRoot.firstChild.remove();
      }

      tableRoot.appendChild(tableHead);
      tableRoot.appendChild(tableBody);
    }

    const { offsetLeft: positionX, offsetTop: positionY } = chart.canvas;
    tooltipEl.style.opacity = 1;
    tooltipEl.style.left = positionX + tooltip.caretX + 'px';
    tooltipEl.style.top = positionY + tooltip.caretY - 72 + 'px';
    tooltipEl.style.font = tooltip.options.bodyFont.string;
  };

  new Chart(
    canvas,
    {
      type: 'line',
      data: {
        labels: data.map(row => row.date),
        datasets: [
          {
            data: data.map(row => row.price),
            pointBackgroundColor: 'rgba(236, 125, 63, 1)',
            tension: 0.1,
            borderColor: 'rgba(236, 125, 63, 1)',
            fill: 'origin',
            backgroundColor: gradient,
            pointRadius: 4,
            borderWidth: 1.5,
            pointHitRadius: 20,
            clip: false,
            pointHoverRadius: 4
          }
        ]
      },
      options: {
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            enabled: false,
            mode: 'nearest',
            position: 'nearest',
            callbacks: {
              label: function (context) {
                return getLabel(context.dataIndex)
              }
            },
            external: externalTooltipHandler,
            titleFont: {
              weigt: 'normal',
            },
            bodyFont: {
              weight: 'bold',
            }
          }
        },
        scales: {
          x: {
            display: false,
          },
          y: {
            display: false,
            min: min,
            max: max,
          }
        },
        layout: {
          padding: {
            top: 20,
            left: 6,
            right: 6
          }
        },
      }
    }
  );

  function getLabel(index) {
    const currVal = data[index].price;
    let predVal;

    if (index == 0) {
      return formatPrice(data[index].price) + " ₽";
    }

    if (data[index].date === '') {
      return "+" + formatPrice(data[index].price) + " ₽";
    }

    if (index > 0) {
      predVal = data[index - 1].price;
    } else {
      predVal = 0;
    }

    const difference = currVal - predVal;

    if (difference == 0) {
      return "Цена не менялась";
    }

    const sign = difference > 0 ? "+ " : "- ";
    return sign + formatPrice(Math.abs(difference)) + " ₽";
  }

  function formatPrice(price) {
    const priceStr = price.toString();
    const length = priceStr.length;
    let formattedPrice = '';

    for (let i = length, j = 0; i > 0; i--, j++) {
      formattedPrice = priceStr[i - 1] + formattedPrice;
      if (j % 3 == 2) {
        formattedPrice = ' ' + formattedPrice;
      }
    }

    return formattedPrice;
  }
}

document.addEventListener('DOMContentLoaded', () => {
  loadChart(document);
})