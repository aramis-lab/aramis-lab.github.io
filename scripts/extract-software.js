const fs = require('fs');
const path = require('path');
const { JSDOM } = require('jsdom');

// Load software.html
const html = fs.readFileSync(
  path.join(__dirname, '..', 'docs', 'pages', 'software.html'),
  'utf-8'
);

const dom = new JSDOM(html);
const doc = dom.window.document;

const software = [];

// Find all software sections (each starts with h1.softtitle)
const contentDiv = doc.querySelector('.entry-content') || doc.querySelector('#post-620');
if (contentDiv) {
  const sections = contentDiv.querySelectorAll('h1.softtitle');
  sections.forEach(h1 => {
    const name = h1.textContent.trim();
    let icon = '', description = '', references = [], links = {};

    // Get the next siblings until next h1
    let next = h1.nextElementSibling;
    while (next && next.tagName !== 'H1') {
      // Look for icon
      if (next.querySelector('img') && !icon) {
        const img = next.querySelector('img');
        icon = img.src.replace('../', '');
      }
      // Description
      if (next.querySelector('.softdescription')) {
        description = next.querySelector('.softdescription').innerHTML.trim();
      }
      // References
      if (next.textContent.includes('References')) {
        const lis = next.querySelectorAll('li');
        lis.forEach(li => {
          const text = li.textContent.trim();
          const pdfLink = li.querySelector('a.pdf-color');
          references.push({
            title: text.split('–')[0]?.split('.').slice(-1)[0]?.trim() || text.substring(0, 100),
            venue: '',
            year: '',
            doi: '',
            pdf: pdfLink ? pdfLink.href : ''
          });
        }
      }
      // Links (github, website, email)
      const linkIcons = next.querySelectorAll('.su-button-center a, .tmm_sociallink');
      linkIcons.forEach(a => {
        const href = a.href;
        const img = a.querySelector('img');
        const src = img ? img.src : '';
        if (src.includes('github')) links.github = href;
        else if (src.includes('website')) links.website = href;
        else if (src.includes('email')) links.email = href.replace('mailto:', '').replace(/\(at\)/g, '@').replace(/\(dot\)/g, '.');
      });
      next = next.nextElementSibling;
    }

    if (name) {
      software.push({
        name,
        icon,
        description: description.replace(/<[^>]+>/g, '').trim(),
        references: references.slice(0, 5),
        ...links
      });
    }
  });
}

console.log(`Extracted ${software.length} software projects`);

// Output YAML
const yaml = software.map(s => {
  return `- name: "${s.name}"
  icon: "${s.icon}"
  description: |
    ${s.description.split('\n').map(l => '    ' + l).join('\n')}
  references:
${s.references.map(r => `    - title: "${r.title}"
      venue: "${r.venue}"
      year: ${r.year || 'null'}
      doi: "${r.doi}"
      pdf: "${r.pdf}"`).join('\n')}
  github: "${s.github || ''}"
  website: "${s.website || ''}"
  email: "${s.email || ''}"`;
}).join('\n');

fs.writeFileSync(
  path.join(__dirname, '..', 'docs', 'data', 'software.yaml'),
  yaml
);

console.log('Software YAML written');