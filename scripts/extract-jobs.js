const fs = require('fs');
const path = require('path');
const { JSDOM } = require('jsdom');

// Load job_offers.html
const html = fs.readFileSync(
  path.join(__dirname, '..', 'docs', 'pages', 'job_offers.html'),
  'utf-8'
);

const dom = new JSDOM(html);
const doc = dom.window.document;

const jobs = [];

// Find job entries in the page
const contentDiv = doc.querySelector('.entry-content');
if (contentDiv) {
  const items = contentDiv.querySelectorAll('li');

  items.forEach(li => {
    const text = li.textContent.trim();
    if (!text || text.length < 10) return;

    // Extract link
    const link = li.querySelector('a');
    const pdf = link ? link.getAttribute('href')?.replace('../', '') : '';
    const title = link ? link.textContent.trim() : text.split('\n')[0];

    // Parse metadata
    let year = 2026, month = 1, duration = '', start_date = '', contact = '', category = 'phd';
    const fullText = li.innerHTML;

    // Extract year from PDF path
    const yearMatch = pdf.match(/(\d{4})\//);
    if (yearMatch) year = parseInt(yearMatch[1]);

    // Extract duration
    const durMatch = fullText.match(/Duration:\s*([^<]+)/i);
    if (durMatch) duration = durMatch[1].trim();

    // Extract start date
    const startMatch = fullText.match(/Starting date:\s*([^<]+)/i);
    if (startMatch) start_date = startMatch[1].trim();

    // Extract contact
    const contactMatch = fullText.match(/Contact:\s*([^<]+)/i);
    if (contactMatch) contact = contactMatch[1].trim().replace(/\[at\]/g, '@').replace(/\[dot\]/g, '.');

    // Determine category from section
    let category = 'phd';
    const prevH2 = li.closest('ul')?.previousElementSibling;
    if (prevH2) {
      const h2Text = prevH2.textContent.toLowerCase();
      if (h2Text.includes('postdoc')) category = 'postdoc';
      else if (h2Text.includes('phd')) category = 'phd';
      else if (h2Text.includes('engineer') || h2Text.includes('software')) category = 'engineer';
      else if (h2Text.includes('intern')) category = 'intern';
    }

    // Set active to true for 2026 jobs
    const active = year >= 2026;

    jobs.push({
      title: title.replace(/"/g, '\\"'),
      year,
      month,
      category,
      duration: duration.replace(/"/g, '\\"'),
      start_date,
      contact: contact.replace(/"/g, '\\"'),
      pdf: pdf.replace(/"/g, '\\"'),
      active,
      description: ''
    });
  });
}

console.log(`Extracted ${jobs.length} job offers`);

// Output YAML
const yaml = jobs.map(j => {
  return `- title: "${j.title}"
  year: ${j.year}
  month: ${j.month}
  category: "${j.category}"
  duration: "${j.duration}"
  start_date: "${j.start_date}"
  contact: "${j.contact}"
  pdf: "${j.pdf}"
  active: ${j.active}
  description: "${j.description}"`;
}).join('\n');

fs.writeFileSync(
  path.join(__dirname, '..', 'docs', 'data', 'jobs.yaml'),
  yaml
);

console.log('Jobs YAML written');