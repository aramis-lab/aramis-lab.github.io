const fs = require('fs');
const path = require('path');
const { JSDOM } = require('jsdom');

// Load people.html
const html = fs.readFileSync(
  path.join(__dirname, '..', 'docs', 'pages', 'people.html'),
  'utf-8'
);

const dom = new JSDOM(html);
const doc = dom.window.document;

const people = [];

// Find all team member divs
const members = doc.querySelectorAll('.tmm_member');

members.forEach(member => {
  const photoDiv = member.querySelector('.tmm_photo');
  const nameDiv = member.querySelector('.tmm_names');
  const roleDiv = member.querySelector('.tmm_job');
  const linksDiv = member.querySelector('.tmm_scblock');

  let name = '';
  let fname = '', lname = '';
  if (nameDiv) {
    fname = nameDiv.querySelector('.tmm_fname')?.textContent?.trim() || '';
    lname = nameDiv.querySelector('.tmm_lname')?.textContent?.trim() || '';
    name = `${fname} ${lname}`.trim();
  }

  let role = roleDiv?.textContent?.trim() || '';

  // Extract photo from background-image
  let photo = '';
  if (photoDiv) {
    const style = photoDiv.getAttribute('style') || '';
    const match = style.match(/url\(['"]?([^'"]+)['"]?\)/);
    if (match) {
      photo = match[1].replace('../', '');
    }
  }

  // Extract social links
  let website = '', email = '', scholar = '', linkedin = '', twitter = '';
  if (linksDiv) {
    linksDiv.querySelectorAll('a').forEach(a => {
      const href = a.getAttribute('href') || '';
      const img = a.querySelector('img');
      const src = img ? img.getAttribute('src') : '';
      if (src.includes('website') || src.includes('customlink')) website = href;
      else if (src.includes('email') || href.includes('mailto')) email = href.replace('mailto:', '');
      else if (src.includes('scholar') || src.includes('customlink')) scholar = href;
      else if (src.includes('linkedin')) linkedin = href;
      else if (src.includes('twitter')) twitter = href;
    });
  }

  // Determine category from parent container class
  let category = 'faculty';
  const container = member.closest('.tmm_container');
  if (container) {
    const containerClass = container.className;
    if (containerClass.includes('phd')) category = 'phd';
    else if (containerClass.includes('postdoc')) category = 'postdocs';
    else if (containerClass.includes('engineer')) category = 'engineers';
    else if (containerClass.includes('admin') || containerClass.includes('support')) category = 'support staff';
    else if (containerClass.includes('alumni') || containerClass.includes('former')) category = 'alumni';
    else if (containerClass.includes('faculty')) category = 'faculty';
  }

  // Also check for section header
  const section = member.closest('.entry-content')?.querySelector('h2');
  if (section) {
    const h2Text = section.textContent.toLowerCase();
    if (h2Text.includes('postdoc')) category = 'postdocs';
    else if (h2Text.includes('phd') || h2Text.includes('ph.d')) category = 'phd';
    else if (h2Text.includes('engineer') || h2Text.includes('software')) category = 'engineers';
    else if (h2Text.includes('admin') || h2Text.includes('support')) category = 'support staff';
    else if (h2Text.includes('alumni') || h2Text.includes('former')) category = 'alumni';
    else if (h2Text.includes('faculty')) category = 'faculty';
  }

  if (name) {
    people.push({
      name: name.replace(/"/g, '\\"'),
      role: role.replace(/"/g, '\\"'),
      category,
      photo: photo.replace(/"/g, '\\"'),
      email: email.replace(/"/g, '\\"'),
      website: website.replace(/"/g, '\\"'),
      scholar: scholar.replace(/"/g, '\\"'),
      linkedin: linkedin.replace(/"/g, '\\"'),
      twitter: twitter.replace(/"/g, '\\"')
    });
  }
});

console.log(`Extracted ${people.length} team members`);

// Count by category
const counts = {};
people.forEach(p => { counts[p.category] = (counts[p.category] || 0) + 1; });
console.log('Categories:', counts);

// Output YAML
const yaml = people.map(p => {
  return `- name: "${p.name}"
  role: "${p.role}"
  category: "${p.category}"
  photo: "${p.photo}"
  email: "${p.email}"
  website: "${p.website}"
  scholar: "${p.scholar}"
  linkedin: "${p.linkedin}"
  twitter: "${p.twitter}"`;
}).join('\n');

fs.writeFileSync(
  path.join(__dirname, '..', 'docs', 'data', 'people.yaml'),
  yaml
);

console.log('People YAML written');