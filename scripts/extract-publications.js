const fs = require('fs');
const path = require('path');
const { JSDOM } = require('jsdom');

// Load publications.html
const html = fs.readFileSync(
  path.join(__dirname, '..', 'docs', 'pages', 'publications.html'),
  'utf-8'
);

const dom = new JSDOM(html);
const doc = dom.window.document;

const publications = [];

// Find all publication list items
const items = doc.querySelectorAll('.entry-content li');

items.forEach(li => {
  const text = li.textContent.trim();
  if (!text || text.length < 30) return;

  // Try to parse: Authors. Year. Title. Venue. DOI. PDF link
  const fullText = li.innerHTML;
  const link = li.querySelector('a[href*="doi.org"]');
  const pdfLink = li.querySelector('a[href*="hal.science"], a[href*="inria.hal.science"], a[href*="hal-"]');
  const pdfIcon = li.querySelector('img[src*="pdf"]');

  let title = '';
  let authors = '';
  let venue = '';
  let year = null;
  let doi = '';
  let pdf = '';
  let highlight = false;

  // Check for highlight class on parent
  const parent = li.closest('.classictext');
  if (parent && parent.querySelector('.publication-highlight')) {
    highlight = true;
  }

  // Extract DOI
  if (link) {
    doi = link.getAttribute('href').replace('https://doi.org/', '');
  }

  // Extract PDF
  if (pdfLink) {
    pdf = pdfLink.getAttribute('href');
  }

  // Parse the text content
  // Format: Authors. Year. 'Title'. Venue. DOI.
  const parts = text.split('.').map(p => p.trim()).filter(p => p);
  if (parts.length >= 4) {
    // Last parts are venue, year, etc.
    // Find year (4 digits)
    const yearIdx = parts.findIndex(p => /^\d{4}$/.test(p.trim()));
    if (yearIdx > 0) {
      year = parseInt(parts[yearIdx]);
      authors = parts.slice(0, yearIdx).join('. ');
      // Title is often in quotes
      const titlePart = parts[yearIdx + 1];
      if (titlePart) {
        const quoteMatch = titlePart.match(/['"](.+)['"]/);
        if (quoteMatch) title = quoteMatch[1];
        else title = titlePart;
      }
      // Venue is remaining parts
      venue = parts.slice(yearIdx + 2).join('. ');
    } else {
      // Fallback: first part is authors, find title in quotes
      authors = parts[0];
      const fullText = parts.join('. ');
      const quoteMatch = fullText.match(/['"](.+?)['"]/);
      if (quoteMatch) {
        title = quoteMatch[1];
        // Find what comes after title
        const afterTitle = fullText.split(quoteMatch[0])[1];
        const venueMatch = afterTitle.match(/([^.]+\.\s*\d{4})/);
        if (venueMatch) {
          venue = venueMatch[1];
        }
      }
    }
  }

  // Determine axis from section header
  let axis = '';
  const section = li.closest('div.classictext')?.previousElementSibling;
  if (section && section.tagName === 'H2') {
    const h2Text = section.textContent.toLowerCase();
    if (h2Text.includes('neuroimaging')) axis = 'neuroimaging-biomarkers';
    else if (h2Text.includes('disease progression')) axis = 'disease-progression';
    else if (h2Text.includes('high-dimensional')) axis = 'multimodal-data';
    else if (h2Text.includes('neuro-epidemiology')) axis = 'neuro-epidemiology';
    else if (h2Text.includes('computational pathology')) axis = 'computational-pathology';
    else if (h2Text.includes('software development')) axis = 'software-development';
  }

  if (title || authors) {
    publications.push({
      title: title.replace(/"/g, '\\"'),
      authors: authors.split(', ').map(a => a.trim()).filter(a => a),
      venue: venue.replace(/"/g, '\\"'),
      year,
      doi: doi.replace(/"/g, '\\"'),
      pdf: pdf.replace(/"/g, '\\"'),
      highlight,
      axis
    });
  }
});

console.log(`Extracted ${publications.length} publications`);

// Output YAML
const yaml = publications.map(p => {
  const authorsYaml = p.authors.map(a => `    - "${a.replace(/"/g, '\\"')}"`).join('\n');
  return `- title: "${p.title}"
  authors:
${authorsYaml}
  venue: "${p.venue}"
  year: ${p.year || 'null'}
  doi: "${p.doi}"
  pdf: "${p.pdf}"
  highlight: ${p.highlight}
  axis: "${p.axis}"`;
}).join('\n');

fs.writeFileSync(
  path.join(__dirname, '..', 'docs', 'data', 'publications.yaml'),
  yaml
);

console.log('Publications YAML written');