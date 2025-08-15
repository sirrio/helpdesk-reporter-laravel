import jsPDF from 'jspdf'
import Chart from 'chart.js/auto'

// Generates a PNG image data (base64) for a chart
export function generateChartImage(type, data, options, width = 400, height = 200) {
  const canvas = document.createElement('canvas')
  canvas.style.width = width + 'px'
  canvas.style.height = height + 'px'
  const pixelRatio = 2
  canvas.width = width * pixelRatio
  canvas.height = height * pixelRatio
  const ctx = canvas.getContext('2d')
  const exportOptions = {
    ...options,
    animation: false,
    responsive: false,
    maintainAspectRatio: false,
    devicePixelRatio: pixelRatio
  }
  const chart = new Chart(ctx, { type, data, options: exportOptions })
  chart.update()
  const dataURL = canvas.toDataURL('image/png')
  return dataURL.split(',')[1]
}

// Helper to add a chart image to the PDF
function addChartImage(pdf, type, data, options, x, y, width, height) {
  const img = generateChartImage(type, data, options, width * 2, height * 2)
  pdf.addImage(img, 'PNG', x, y, width, height)
}

// Helper to merge legend display and layout settings
function withLegend(baseOptions, position, labelSize = 12) {
  return {
    ...baseOptions,
    plugins: {
      ...baseOptions.plugins,
      legend: {
        display: true,
        position,
        labels: { font: { size: labelSize }, boxWidth: 12, padding: 8 }
      }
    },
    layout: { padding: { bottom: 50 } }
  }
}

// Creates the PDF document for the provided chart data and layout parameters
export function createStatisticsPdf({
  currentSem,
  total,
  avgPerWeek,
  topFaculty,
  weekCharts,
  facultyChartData,
  facultyChartOptions,
  degreeChartData,
  degreeChartOptions,
  topicChartData,
  topicChartOptions
}) {
  const pdf = new jsPDF()
  const now = new Date()
  const pageWidth = pdf.internal.pageSize.getWidth()
  const dateString = now.toLocaleDateString('de-DE')

  // Header (centered vertically on the page with downward offset)
  const pageHeight = pdf.internal.pageSize.getHeight()
  const centerY = pageHeight / 2
  const lineSpacing = 10
  const totalLines = 5
  // Calculate starting Y to center block, then shift down by offsetY
  const offsetY = 10
  let y = centerY - ((totalLines - 1) * lineSpacing) / 2 + offsetY

  // Title with semester value only
  pdf.setFont('helvetica', 'bold')
  pdf.setFontSize(18)
  pdf.text(`Statistik - ${currentSem}`, pageWidth / 2, y, { align: 'center' })
  // Date
  y += lineSpacing
  pdf.setFont('helvetica', 'normal')
  pdf.setFontSize(12)
  pdf.text(`${dateString}`, pageWidth / 2, y, { align: 'center' })
  // KPIs (add extra space from date)
  y += lineSpacing + 10
  pdf.text(`Gesamtanzahl: ${total}`, pageWidth / 2, y, { align: 'center' })
  y += lineSpacing
  pdf.text(`Durchschnitt pro Woche: ${avgPerWeek}`, pageWidth / 2, y, { align: 'center' })
  y += lineSpacing
  pdf.text(`Top-Fakultät: ${topFaculty}`, pageWidth / 2, y, { align: 'center' })

  // Wochenübersicht
  pdf.addPage()
  const startY = 15
  pdf.setFontSize(14)
  pdf.text('Wochenübersicht', 10, startY)
  let yPos = startY + 8
  const chartWidth = 90
  const chartHeight = 80
  const rowHeight = chartHeight + 8
  weekCharts.forEach((weekChart, index) => {
    if (index > 0 && index % 2 === 0) {
      yPos += rowHeight
      if (yPos + chartHeight + 12 > pageHeight) {
        pdf.addPage()
        yPos = startY
        pdf.setFontSize(14)
        pdf.text('Wochenübersicht (Fortsetzung)', 10, yPos)
        yPos += 8
      }
    }
    const col = index % 2
    const xPos = col === 0
      ? pageWidth / 4 - chartWidth / 2
      : (3 * pageWidth) / 4 - chartWidth / 2
    addChartImage(pdf, 'bar', weekChart.chartData, weekChart.chartOptions, xPos, yPos, chartWidth, chartHeight)
  })
  y = yPos + rowHeight + 12

  // Verteilungen Fachbereiche
  const distW = 140
  const distH = 100
  const xCenter = (pageWidth - distW) / 2
  pdf.addPage()
  y = 15
  pdf.setFontSize(14)
  pdf.text('Verteilungen', 10, y)
  y += 10
  pdf.setFontSize(12)
  pdf.text('Fachbereiche', 10, y)
  y += 6
  const famOptPDF = withLegend(facultyChartOptions, 'bottom')
  addChartImage(pdf, 'polarArea', facultyChartData, famOptPDF, xCenter, y, distW, distH)

  // Studiengänge
  y += distH + 20
  pdf.setFontSize(12)
  pdf.text('Studiengänge', 10, y)
  y += 6
  const degW = pageWidth - 20
  const degH = 120
  const degOptPDF = {
    ...degreeChartOptions,
    layout: { padding: { left: 40, right: 40, bottom: 50 } },
    scales: {
      x: { ticks: { autoSkip: false, maxRotation: 70, minRotation: 70, font: { size: 10 } } },
      y: degreeChartOptions.scales.y
    }
  }
  addChartImage(pdf, 'bar', degreeChartData, degOptPDF, 10, y, degW, degH)

  // Themen Verteilung
  pdf.addPage()
  // Reset y position and draw header for topics
  y = 20
  pdf.setFontSize(12)
  pdf.text('Themen', 10, y)
  const topOptPDF = withLegend(topicChartOptions, 'bottom', 12)
  addChartImage(pdf, 'pie', topicChartData, topOptPDF, xCenter, y, distW, distH)

  // Add footer with semester, date and page count on each page
  const pageCount = pdf.internal.getNumberOfPages()
  pdf.setFontSize(10)
  for (let pageNum = 1; pageNum <= pageCount; pageNum++) {
    pdf.setPage(pageNum)
    const footerY = pdf.internal.pageSize.getHeight() - 10
    // Left: Semester
    pdf.text(`Semester: ${currentSem}`, 10, footerY)
    // Center: Date
    pdf.text(`${dateString}`, pageWidth / 2, footerY, { align: 'center' })
    // Right: Page x of y
    pdf.text(`Seite ${pageNum}/${pageCount}`, pageWidth - 10, footerY, { align: 'right' })
  }

  return pdf
}

// Opens the generated PDF in a new tab
export function exportStatisticsPdf(params) {
  const pdf = createStatisticsPdf(params)
  const pdfBlob = pdf.output('blob')
  const blobURL = URL.createObjectURL(pdfBlob)
  window.open(blobURL, '_blank')
} 