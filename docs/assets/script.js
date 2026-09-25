// ARAMIS Lab Website - Main JavaScript
// Loads YAML data and renders components

// CDN dependencies loaded via HTML: js-yaml, marked

const DataLoader = {
  cache: {},

  async loadYAML(path) {
    if (this.cache[path]) return this.cache[path];
    try {
      const response = await fetch(path);
      if (!response.ok) throw new Error(`Failed to load ${path}: ${response.status}`);
      const text = await response.text();
      const data = jsyaml.load(text);
      this.cache[path] = data;
      return data;
    } catch (e) {
      console.error(`Error loading ${path}:`, e);
      return {};
    }
  }
};

const Renderers = {
  // Team grid with category filtering
  TeamGrid: {
    categories: ['faculty', 'postdocs', 'phd', 'engineers', 'interns', 'support staff'],
    categoryLabels: {
      'faculty': 'PIs',
      'postdocs': 'Postdoctoral Researchers',
      'phd': 'PhD Students',
      'engineers': 'Research Engineers',
      'interns': 'Interns',
      'support staff': 'Support Staff'
    },

    init(containerId, dataPath) {
      this.container = document.getElementById(containerId);
      this.dataPath = dataPath;
      this.currentFilter = 'all';
      this.filtersRendered = false;
      this.loadData();
    },

    renderFilters() {
      if (this.filtersRendered) return;
      const filterContainer = document.createElement('div');
      filterContainer.className = 'team-filters';
      filterContainer.innerHTML = `
        <button class="team-filter active" data-filter="all">All</button>
        ${this.categories.map(cat => `
          <button class="team-filter" data-filter="${cat}">${this.categoryLabels[cat]}</button>
        `).join('')}
      `;
      this.container.parentNode.insertBefore(filterContainer, this.container);

      filterContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('team-filter')) {
          filterContainer.querySelectorAll('.team-filter').forEach(btn => btn.classList.remove('active'));
          e.target.classList.add('active');
          this.currentFilter = e.target.dataset.filter;
          this.render(this.data);
        }
      });
      this.filtersRendered = true;
    },

    async loadData() {
      this.data = await DataLoader.loadYAML(this.dataPath);
      this.renderFilters();
      this.render(this.data);
    },

    render(data) {
      const filtered = this.currentFilter === 'all'
        ? data.filter(p => !['alumni'].includes(p.category))
        : data.filter(p => p.category === this.currentFilter);

      // Sort by category order first, then alphabetically by last name within each category
      const categoryOrder = { faculty: 0, postdocs: 1, phd: 2, engineers: 3, interns: 4, 'support staff': 5 };
      
      // Known compound last names (French names with particles)
      const compoundNames = {
        'Tezenas du Montcel': 'Tezenas du Montcel'
      };
      
      function getLastName(name) {
        // Check for known compound names
        for (const [full, last] of Object.entries(compoundNames)) {
          if (name.includes(full)) return last.toLowerCase();
        }
        // Default: last word
        return name.split(' ').pop().toLowerCase();
      }
      
      filtered.sort((a, b) => {
        const catA = categoryOrder[a.category] ?? 99;
        const catB = categoryOrder[b.category] ?? 99;
        if (catA !== catB) return catA - catB;
        const lastNameA = getLastName(a.name);
        const lastNameB = getLastName(b.name);
        return lastNameA.localeCompare(lastNameB);
      });

      this.container.innerHTML = filtered.map(person => this.cardHTML(person)).join('');
    },

    cardHTML(p) {
      const placeholderImg = 'images/people/carre/unknown.png';
      const photo = p.photo ? p.photo : placeholderImg;
      const iconWebsite = `<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>`;
      const iconEmail = `<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>`;
      const iconScholar = `<svg viewBox="0 0 24 24" width="18" height="18" fill="var(--color-secondary)"><path d="M3 7.5L12 3l9 4.5-9 4.5L3 7.5Z"/><path d="M7 10.2v5.4c0 1.6 2.2 3 5 3s5-1.4 5-3v-5.4" stroke="var(--color-secondary)" stroke-width="1.5" stroke-linecap="round"/><path d="M21 7.5v8" stroke="var(--color-secondary)" stroke-width="1.5" stroke-linecap="round"/></svg>`;
      const iconLinkedIn = `<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>`;
      const iconTwitter = `<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M22.46 6c-.87.39-1.83.65-2.83.77 1.02-.6 1.8-1.55 2.17-2.68-.95.56-2 .97-3.13 1.19-.9-.95-2.18-1.55-3.59-1.55-2.72 0-4.93 2.21-4.93 4.93 0 .39.04.77.13 1.13-4.1-.2-7.74-2.17-10.2-5.14-.42.73-.66 1.58-.66 2.48 0 1.71.87 3.21 2.19 4.1-.8-.03-1.56-.25-2.22-.61v.06c0 2.39 1.7 4.38 3.95 4.83-.41.11-.84.17-1.29.17-.32 0-.63-.03-.93-.09.63 1.97 2.46 3.4 4.6 3.44-1.68 1.32-3.8 2.1-6.1 2.1-.4 0-.79-.02-1.18-.07 2.18 1.4 4.76 2.22 7.55 2.22 9.05 0 14-7.5 14-14 0-.21 0-.43-.01-.64.96-.69 1.8-1.56 2.46-2.55z"/></svg>`;

      return `
        <article class="team-card">
          <img class="team-photo" src="${photo}" alt="${p.name}" loading="lazy" onerror="this.src='${placeholderImg}'">
          <h3 class="team-name">${p.name}</h3>
          <p class="team-role">${p.role}</p>
          <div class="team-links">
            ${p.website ? `<a href="${p.website}" target="_blank" rel="noopener" aria-label="${p.name} website">${iconWebsite}</a>` : ''}
            ${p.email ? `<a href="mailto:${p.email}" aria-label="Email ${p.name}">${iconEmail}</a>` : ''}
            ${p.scholar ? `<a href="${p.scholar}" target="_blank" rel="noopener" aria-label="${p.name} Google Scholar">${iconScholar}</a>` : ''}
            ${p.linkedin ? `<a href="${p.linkedin}" target="_blank" rel="noopener" aria-label="${p.name} LinkedIn">${iconLinkedIn}</a>` : ''}
            ${p.twitter ? `<a href="${p.twitter}" target="_blank" rel="noopener" aria-label="${p.name} Twitter">${iconTwitter}</a>` : ''}
          </div>
        </article>
      `;
    },

    // Render alumni list
    async renderAlumni(containerId) {
      const container = document.getElementById(containerId);
      if (!container) return;

      // Load former members from CSV
      const response = await fetch('data/aramis_former_members.csv');
      const csvText = await response.text();
      
      // Parse CSV handling quoted fields
      const parseCSV = (text) => {
        const lines = text.trim().split('\n');
        const result = [];
        let current = '';
        let inQuotes = false;
        
        for (const line of lines) {
          let field = '';
          const fields = [];
          
          for (let i = 0; i < line.length; i++) {
            const char = line[i];
            if (char === '"') {
              inQuotes = !inQuotes;
            } else if (char === ',' && !inQuotes) {
              fields.push(field);
              field = '';
            } else {
              field += char;
            }
          }
          fields.push(field);
          result.push(fields);
        }
        return result;
      };
      
      const rows = parseCSV(csvText);
      const headers = rows[0].map(h => h.trim());
      
      const alumni = rows.slice(1).map(values => {
        const obj = {};
        headers.forEach((h, i) => obj[h] = values[i] ? values[i].trim() : '');
        return obj;
      });

      // Group by year left
      const groups = {};
      alumni.forEach(member => {
        const year = member['Year left'] || 'Unknown';
        if (!groups[year]) groups[year] = [];
        groups[year].push({
          name: member.Name,
          role: member.Status
        });
      });

      // Sort years: numeric years descending (most recent first), "Nerv team in 2024" just after 2024, then "2018 and before" last
      const sortedYears = Object.keys(groups).sort((a, b) => {
        if (a === '2018 and before') return 1;
        if (b === '2018 and before') return -1;
        
        // Handle "Nerv team in 2024" - should come right after "2024"
        if (a === 'Nerv team in 2024' && b === '2024') return 1;  // a after b
        if (b === 'Nerv team in 2024' && a === '2024') return -1; // a before b
        
        // For other comparisons with "Nerv team in 2024", treat it as 2024.5
        const getYearVal = (y) => {
          if (y === 'Nerv team in 2024') return 2024.5;
          return parseInt(y) || -1;
        };
        
        const ya = getYearVal(a);
        const yb = getYearVal(b);
        return yb - ya;
      });

      let html = '';
      for (const year of sortedYears) {
        const members = groups[year];
        const title = year === '2018 and before' ? 'Left in 2018 or before' : 
                      year === 'Nerv team in 2024' ? 'Left for NERV team in 2024' : 
                      `Left in ${year}`;
        html += `
          <h3 style="margin-top: 2rem; margin-bottom: 1rem; color: var(--color-secondary);">${title}</h3>
          <ul style="list-style: none; padding: 0; max-width: 600px; margin: 0 auto; font-size: 0.875rem;">
            ${members.map(m => `<li style="padding: 0.375rem 0; border-bottom: 1px solid var(--color-border);"><strong>${m.name}</strong> - ${m.role}</li>`).join('')}
          </ul>
        `;
      }

      container.innerHTML = html;
    }
  },

  // Publication list grouped by research axis (category)
  PublicationList: {
    init(containerId, dataPath) {
      this.container = document.getElementById(containerId);
      this.dataPath = dataPath;
      this.loadData();
    },

    async loadData() {
      const data = await DataLoader.loadYAML(this.dataPath);
      this.render(data);
    },

    render(data) {
      // Group by axis
      const axisOrder = [
        'representation-learning',
        'disease-progression',
        'methodological-challenges',
        'computational-pathology',
        'reproducibility-validation',
        'clinical-translation'
      ];
      const axisLabels = {
        'representation-learning': 'Representation Learning for Multimodal Medical Data',
        'disease-progression': 'Modelling Disease Progression from Longitudinal Data',
        'methodological-challenges': 'Addressing Methodological Challenges of Real-World Data',
        'computational-pathology': 'Computational Pathology and High-Content Microscopy',
        'reproducibility-validation': 'Reproducibility, Benchmarking and Validation: Rigorous Practices to Increase Impact',
        'clinical-translation': 'Translating Computational Innovation into Medical Research and Clinical Practice'
      };

      const byAxis = data.reduce((acc, pub) => {
        const axis = pub.axis || 'Other';
        if (!acc[axis]) acc[axis] = [];
        acc[axis].push(pub);
        return acc;
      }, {});

      // Axes in display order, then any not listed in axisOrder
      const axes = axisOrder.filter(axis => byAxis[axis] && byAxis[axis].length)
        .concat(Object.keys(byAxis).filter(axis => !axisOrder.includes(axis) && byAxis[axis].length));

      // Dropdown to jump to each axis section
      let html = `
        <nav class="axis-nav" aria-label="Research axes">
          <select class="axis-select" aria-label="Jump to research theme">
            <option value="">Jump to research theme&hellip;</option>
            ${axes.map(axis => `<option value="${axis}">${axisLabels[axis] || axis}</option>`).join('')}
          </select>
        </nav>
      `;
      axes.forEach(axis => {
        html += `<h2 class="axis-title" id="${axis}">${axisLabels[axis] || axis}</h2>`;
        html += byAxis[axis].map(pub => this.itemHTML(pub)).join('');
      });

      this.container.innerHTML = html;

      const select = this.container.querySelector('.axis-select');
      select.addEventListener('change', () => {
        if (!select.value) return;
        const target = document.getElementById(select.value);
        history.replaceState(null, '', `#${select.value}`);
        target.scrollIntoView({ behavior: 'smooth' });
        select.value = '';
      });

      // Handle hash navigation after rendering
      if (window.location.hash) {
        const target = this.container.querySelector(window.location.hash);
        if (target) target.scrollIntoView();
      }
    },

    itemHTML(p) {
      const authorsList = Array.isArray(p.authors) ? p.authors : [p.authors];
      const formatAuthor = (name) => {
        const parts = name.split(', ');
        if (parts.length === 2) {
          const lastName = parts[0];
          const firstName = parts[1];
          return `${lastName} ${firstName.charAt(0)}.`;
        }
        return name;
      };
      const formattedAuthors = authorsList.map(formatAuthor);
      const authors = formattedAuthors.length > 1
        ? formattedAuthors.slice(0, -1).join(', ') + ', ' + formattedAuthors.slice(-1)
        : formattedAuthors[0];
      const venue = p.venue || '';
      const volume = p.volume ? `;${p.volume}` : '';
      const pages = p.pages ? `:${p.pages}` : '';
      const iconPDF = `<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>`;
      
      return `
        <article class="publication-item">
          <h4 class="publication-title">${p.title}</h4>
          <p class="publication-meta">${authors}. <em>${venue}</em> ${p.year}${volume}${pages}. ${p.doi ? `<a href="https://doi.org/${p.doi}" target="_blank" rel="noopener">doi:${p.doi}</a>` : ''}${p.pdf ? ` <a class="pdf-link" href="${p.pdf}" target="_blank" rel="noopener">${iconPDF}</a>` : ''}</p>
        </article>
      `;
    }
  },

  // Software list (vertical cards, not grid)
  SoftwareGrid: {
    init(containerId, dataPath) {
      this.container = document.getElementById(containerId);
      this.dataPath = dataPath;
      this.loadData();
    },

    async loadData() {
      const data = await DataLoader.loadYAML(this.dataPath);
      this.render(data);
    },

    render(data) {
      this.container.innerHTML = data.map(soft => this.cardHTML(soft)).join('');
    },

    cardHTML(s) {
      const icon = s.icon ? s.icon : '';
      const iconDark = s.iconDark ? s.iconDark : '';
      const refs = s.references && s.references.length ? `
        <details class="accordion">
          <summary>References (${s.references.length})</summary>
          <div class="accordion-content">
            ${s.references.map(r => `<p>${r.authors ? r.authors + '. ' : ''}<em>${r.title}</em>. ${r.venue}, ${r.year}. ${r.doi ? `<a href="https://doi.org/${r.doi}" target="_blank" rel="noopener">${r.doi}</a>` : ''}${r.pdf ? ` <a class="pdf-link" href="${r.pdf}" target="_blank" rel="noopener">PDF</a>` : ''}</p>`).join('')}
          </div>
        </details>
      ` : '';

      const iconGitHub = `<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 .5C5.73.5.5 5.73.5 12c0 5.08 3.29 9.39 7.86 10.91.58.1.79-.25.79-.56 0-.27-.01-1-.02-1.96-3.2.7-3.87-1.54-3.87-1.54-.52-1.33-1.28-1.69-1.28-1.69-1.04-.71.08-.7.08-.7 1.15.08 1.76 1.18 1.76 1.18 1.03 1.76 2.69 1.25 3.35.96.1-.74.4-1.25.73-1.54-2.55-.29-5.24-1.28-5.24-5.69 0-1.26.45-2.29 1.18-3.1-.12-.29-.51-1.46.11-3.05 0 0 .97-.31 3.17 1.18a11 11 0 0 1 5.77 0c2.2-1.49 3.17-1.18 3.17-1.18.63 1.59.23 2.76.11 3.05.74.81 1.18 1.84 1.18 3.1 0 4.42-2.69 5.39-5.25 5.68.41.36.78 1.06.78 2.14 0 1.55-.01 2.8-.01 3.18 0 .31.21.67.8.56A11.51 11.51 0 0 0 23.5 12C23.5 5.73 18.27.5 12 .5z"/></svg>`;
      const iconWebsite = `<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>`;
      const iconEmail = `<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>`;

      return `
        <article class="software-card">
          <header class="software-header">
            ${icon && iconDark ? `<img class="software-icon logo-light" src="${icon}" alt="${s.name}" loading="lazy" onerror="this.style.display='none'">` : ''}
            ${iconDark ? `<img class="software-icon logo-dark" src="${iconDark}" alt="${s.name}" loading="lazy" onerror="this.style.display='none'">` : ''}
            ${icon && !iconDark ? `<img class="software-icon" src="${icon}" alt="${s.name}" loading="lazy" onerror="this.style.display='none'">` : ''}
            <h3 class="software-title">${s.name}</h3>
          </header>
          <div class="software-desc">${marked.parse(s.description || '')}</div>
          ${refs}
          <div class="software-links">
            ${s.github ? `<a href="${s.github}" target="_blank" rel="noopener">${iconGitHub} GitHub</a>` : ''}
            ${s.website ? `<a href="${s.website}" target="_blank" rel="noopener">${iconWebsite} Website</a>` : ''}
            ${s.email ? `<a href="mailto:${s.email}">${iconEmail} Contact</a>` : ''}
          </div>
        </article>
      `;
    }
  },

  // Job list (active only)
  JobList: {
    init(containerId, dataPath) {
      this.container = document.getElementById(containerId);
      this.dataPath = dataPath;
      this.loadData();
    },

    async loadData() {
      const data = await DataLoader.loadYAML(this.dataPath);
      // Filter active only
      const active = data.filter(j => j.active === true);
      // Sort by year/month descending
      active.sort((a, b) => (b.year * 12 + (b.month || 1)) - (a.year * 12 + (a.month || 1)));
      this.render(active);
    },

    render(data) {
      if (data.length === 0) {
        this.container.innerHTML = '<p style="text-align:center; color: var(--color-text-muted);">No active positions at the moment.</p>';
        return;
      }

      this.container.innerHTML = data.map(job => this.cardHTML(job)).join('');
    },

    cardHTML(j) {
      const dateStr = j.start_date ? new Date(j.start_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long' }) : '';
      const iconPDF = `<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>`;
      const iconEmail = `<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>`;

      return `
        <article class="job-card">
          <header class="job-header">
            <h3 class="job-title">${j.title}</h3>
            <div class="job-meta">
              ${j.duration ? `<span><strong>Duration:</strong> ${j.duration}</span>` : ''}
              ${dateStr ? `<span><strong>Start:</strong> ${dateStr}</span>` : ''}
            </div>
          </header>
          <p class="job-desc">${j.description ? marked.parse(j.description) : 'Details in the PDF below.'}</p>
          <div class="job-links">
            ${j.pdf ? `<a class="pdf-link" href="${j.pdf}" target="_blank" rel="noopener">${iconPDF} View PDF</a>` : ''}
            ${j.contact ? `<a href="mailto:${j.contact}">${iconEmail} Contact: ${j.contact}</a>` : ''}
          </div>
        </article>
      `;
    }
  },

  // Research page renderer (from research.yaml with Markdown)
  ResearchPage: {
    init(containerId, dataPath) {
      this.container = document.getElementById(containerId);
      this.dataPath = dataPath;
      this.loadData();
    },

    async loadData() {
      const data = await DataLoader.loadYAML(this.dataPath);
      this.render(data);
    },

    render(data) {
      let html = '';

      // Context
      if (data.context) {
        html += `
          <section class="research-section">
            <div class="research-content-text">
              ${marked.parse(data.context)}
            </div>
          </section>
        `;
      }

      // Axes
      if (data.axes && data.axes.length) {
        html += `
          <section class="research-section" id="research-axes">
            <h2>Main Research Axes</h2>
            ${data.axes.map(axis => `
              <article class="research-axis">
                <h3>${axis.title}</h3>
                ${axis.pis ? `<p class="pis">PIs involved: ${Array.isArray(axis.pis) ? axis.pis.join(', ') : axis.pis}</p>` : ''}
                <div class="research-content-text">${marked.parse(axis.content || '')}</div>
              </article>
            `).join('')}
          </section>
        `;
      }

      // Collaborations - render as lists, not columns
      if (data.collaborations) {
        const collabs = data.collaborations;
        html += `
          <section class="research-section" id="collaborations">
            <h2>Collaborations</h2>
            ${collabs.international ? this.collabGroup('International', collabs.international) : ''}
            ${collabs.national ? this.collabGroup('National', collabs.national) : ''}
            ${collabs.local ? this.collabGroup('Local', collabs.local) : ''}
          </section>
        `;
      }

      // Funding
      if (data.funding && data.funding.length) {
        html += `
          <section class="research-section" id="funding">
            <h2>Main Funding Sources</h2>
            <ul class="funding-list">
              ${data.funding.map(f => `<li>${f.name}</li>`).join('')}
            </ul>
          </section>
        `;
      }

      // Buttons linking to each section
      const sections = document.createElement('div');
      sections.innerHTML = html;
      const nav = Array.from(sections.querySelectorAll('.research-section[id]'))
        .map(sec => `<a href="#${sec.id}">${sec.querySelector('h2').textContent}</a>`).join('');

      this.container.innerHTML = `<nav class="section-nav" aria-label="Page sections">${nav}</nav>` + html;

      // Handle hash navigation after rendering
      if (window.location.hash) {
        const target = this.container.querySelector(window.location.hash);
        if (target) {
          target.scrollIntoView({ behavior: 'smooth' });
        }
      }
    },

    collabGroup(title, items) {
      return `
        <h3 style="margin-top: 2rem; color: var(--color-secondary);">${title}</h3>
        <ul class="collab-list">
          ${items.map(item => `
            <li><strong>${item.name}</strong> - ${item.pi || ''}${item.focus ? ` - ${item.focus}` : ''}${item.url ? ` (<a href="${item.url}" target="_blank" rel="noopener">Website</a>)` : ''}</li>
          `).join('')}
        </ul>
      `;
    }
  },

  // Light/dark toggle; saved choice overrides the device setting
  ThemeToggle: {
    init() {
      const toggle = document.querySelector('.theme-toggle');
      if (!toggle) return;
      const root = document.documentElement;
      toggle.addEventListener('click', () => {
        const current = root.dataset.theme
          || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        const next = current === 'dark' ? 'light' : 'dark';
        root.dataset.theme = next;
        try { localStorage.setItem('theme', next); } catch (e) {}
      });
    }
  },

  // Mobile navigation toggle
  MobileNav: {
    init() {
      const toggle = document.querySelector('.nav-toggle');
      const nav = document.querySelector('.nav');
      if (!toggle || !nav) return;

      toggle.addEventListener('click', () => {
        const isOpen = nav.classList.toggle('open');
        toggle.setAttribute('aria-expanded', isOpen);
      });

      // Close on link click
      nav.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
          nav.classList.remove('open');
          toggle.setAttribute('aria-expanded', 'false');
        });
      });
    }
  }
};

// Auto-initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
  Renderers.MobileNav.init();
  Renderers.ThemeToggle.init();

  // Initialize components based on container presence
  if (document.getElementById('team-grid')) {
    Renderers.TeamGrid.init('team-grid', 'data/people.yaml');
    Renderers.TeamGrid.renderAlumni('alumni-list');
  }
  if (document.getElementById('pub-list')) {
    Renderers.PublicationList.init('pub-list', 'data/publications.yaml');
  }
  if (document.getElementById('software-grid')) {
    Renderers.SoftwareGrid.init('software-grid', 'data/software.yaml');
  }
  if (document.getElementById('job-list')) {
    Renderers.JobList.init('job-list', 'data/jobs.yaml');
  }
  if (document.getElementById('research-content')) {
    Renderers.ResearchPage.init('research-content', 'data/research.yaml');
  }
});