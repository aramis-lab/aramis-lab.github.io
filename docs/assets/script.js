// ARAMIS Lab Website - Main JavaScript
// Loads YAML data and renders components

// CDN dependencies loaded via HTML: js-yaml, marked

const DataLoader = {
  cache: {},

  async loadYAML(path) {
    if (this.cache[path]) return this.cache[path];
    try {
      const response = await fetch(path);
      if (!response.ok) throw new Error(`Failed to load ${path}`);
      const text = await response.text();
      const data = jsyaml.load(text);
      this.cache[path] = data;
      return data;
    } catch (e) {
      console.error(`Error loading ${path}:`, e);
      return [];
    }
  }
};

const Renderers = {
  // Team grid with category filtering
  TeamGrid: {
    categories: ['faculty', 'postdocs', 'phd', 'engineers', 'support staff', 'alumni'],
    categoryLabels: {
      'faculty': 'Faculty',
      'postdocs': 'Postdocs',
      'phd': 'PhD Students',
      'engineers': 'Engineers',
      'support staff': 'Support Staff',
      'alumni': 'Alumni'
    },

    init(containerId, dataPath) {
      this.container = document.getElementById(containerId);
      this.dataPath = dataPath;
      this.currentFilter = 'all';
      this.renderFilters();
      this.loadData();
    },

    renderFilters() {
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
    },

    async loadData() {
      this.data = await DataLoader.loadYAML(this.dataPath);
      this.render(this.data);
    },

    render(data) {
      const filtered = this.currentFilter === 'all'
        ? data
        : data.filter(p => p.category === this.currentFilter);

      this.container.innerHTML = filtered.map(person => this.cardHTML(person)).join('');
    },

    cardHTML(p) {
      const photo = p.photo ? `images/${p.photo}` : 'images/icons/avatar-placeholder.png';
      return `
        <article class="team-card">
          <img class="team-photo" src="${photo}" alt="${p.name}" loading="lazy">
          <h3 class="team-name">${p.name}</h3>
          <p class="team-role">${p.role}</p>
          <div class="team-links">
            ${p.website ? `<a href="${p.website}" target="_blank" rel="noopener" aria-label="${p.name} website"><img src="images/icons/website.svg" alt=""></a>` : ''}
            ${p.email ? `<a href="mailto:${p.email}" aria-label="Email ${p.name}"><img src="images/icons/email.svg" alt=""></a>` : ''}
            ${p.scholar ? `<a href="${p.scholar}" target="_blank" rel="noopener" aria-label="${p.name} Google Scholar"><img src="images/icons/scholar.svg" alt=""></a>` : ''}
            ${p.linkedin ? `<a href="${p.linkedin}" target="_blank" rel="noopener" aria-label="${p.name} LinkedIn"><img src="images/icons/linkedin.svg" alt=""></a>` : ''}
            ${p.twitter ? `<a href="${p.twitter}" target="_blank" rel="noopener" aria-label="${p.name} Twitter"><img src="images/icons/twitter.svg" alt=""></a>` : ''}
          </div>
        </article>
      `;
    }
  },

  // Publication list grouped by year
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
      // Group by year
      const byYear = data.reduce((acc, pub) => {
        const year = pub.year || 'Other';
        if (!acc[year]) acc[year] = [];
        acc[year].push(pub);
        return acc;
      }, {});

      // Sort years descending
      const years = Object.keys(byYear).sort((a, b) => b - a);

      this.container.innerHTML = years.map(year => `
        <h3 style="margin: 2rem 0 1rem; color: var(--color-secondary);">${year}</h3>
        ${byYear[year].map(pub => this.itemHTML(pub)).join('')}
      `).join('');
    },

    itemHTML(p) {
      const highlightClass = p.highlight ? ' highlight' : '';
      const authors = Array.isArray(p.authors) ? p.authors.join(', ') : p.authors;
      return `
        <article class="publication-item${highlightClass}">
          <h4 class="publication-title">${p.title}</h4>
          <p class="publication-meta">${authors}. ${p.year}. <em>${p.venue}</em>.</p>
          <div class="publication-links">
            ${p.doi ? `<a href="https://doi.org/${p.doi}" target="_blank" rel="noopener">DOI: ${p.doi}</a>` : ''}
            ${p.pdf ? `<a class="pdf-link" href="${p.pdf}" target="_blank" rel="noopener"><img src="images/icons/pdf.svg" alt=""> PDF</a>` : ''}
          </div>
        </article>
      `;
    }
  },

  // Software grid
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
      const icon = s.icon ? `images/${s.icon}` : '';
      const refs = s.references && s.references.length ? `
        <details class="accordion">
          <summary>References (${s.references.length})</summary>
          <div class="accordion-content">
            ${s.references.map(r => `<p>${r.authors ? r.authors + '. ' : ''}<em>${r.title}</em>. ${r.venue}, ${r.year}. ${r.doi ? `<a href="https://doi.org/${r.doi}" target="_blank" rel="noopener">${r.doi}</a>` : ''}${r.pdf ? ` <a class="pdf-link" href="${r.pdf}" target="_blank" rel="noopener"><img src="images/icons/pdf.svg" alt=""> PDF</a>` : ''}</p>`).join('')}
          </div>
        </details>
      ` : '';

      return `
        <article class="software-card">
          ${icon ? `<img class="software-icon" src="${icon}" alt="${s.name}" loading="lazy">` : ''}
          <h3 class="software-title">${s.name}</h3>
          <div class="software-desc">${marked.parse(s.description || '')}</div>
          ${refs}
          <div class="software-links">
            ${s.github ? `<a href="${s.github}" target="_blank" rel="noopener"><img src="images/icons/github.svg" alt=""> GitHub</a>` : ''}
            ${s.website ? `<a href="${s.website}" target="_blank" rel="noopener"><img src="images/icons/website.svg" alt=""> Website</a>` : ''}
            ${s.email ? `<a href="mailto:${s.email}"><img src="images/icons/email.svg" alt=""> Contact</a>` : ''}
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
      return `
        <article class="job-card">
          <header class="job-header">
            <h3 class="job-title">${j.title}</h3>
            <div class="job-meta">
              ${j.category ? `<span><strong>Type:</strong> ${j.category}</span>` : ''}
              ${j.duration ? `<span><strong>Duration:</strong> ${j.duration}</span>` : ''}
              ${dateStr ? `<span><strong>Start:</strong> ${dateStr}</span>` : ''}
            </div>
          </header>
          <p class="job-desc">${j.description ? marked.parse(j.description) : 'Details in the PDF below.'}</p>
          <div class="job-links">
            ${j.pdf ? `<a class="pdf-link" href="${j.pdf}" target="_blank" rel="noopener"><img src="images/icons/pdf.svg" alt=""> View PDF</a>` : ''}
            ${j.contact ? `<a href="mailto:${j.contact}"><img src="images/icons/email.svg" alt=""> Contact: ${j.contact}</a>` : ''}
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
            <div style="max-width: 800px; margin: 0 auto; text-align: left;">
              ${marked.parse(data.context)}
            </div>
          </section>
        `;
      }

      // Axes
      if (data.axes && data.axes.length) {
        html += `
          <section class="research-section">
            <h2>Main Research Axes</h2>
            ${data.axes.map(axis => `
              <article class="research-axis">
                <h3>${axis.title}</h3>
                ${axis.pis ? `<p class="pis">PIs involved: ${Array.isArray(axis.pis) ? axis.pis.join(', ') : axis.pis}</p>` : ''}
                <div style="max-width: 800px; margin: 0 auto; text-align: left;">${marked.parse(axis.content || '')}</div>
              </article>
            `).join('')}
          </section>
        `;
      }

      // Collaborations
      if (data.collaborations) {
        const collabs = data.collaborations;
        html += `
          <section class="research-section">
            <h2>Collaborations</h2>
            <div class="research-collabs">
              ${collabs.external?.methodical ? this.collabGroup('External - Methodological', collabs.external.methodical) : ''}
              ${collabs.external?.medical ? this.collabGroup('External - Medical', collabs.external.medical) : ''}
              ${collabs.local?.methodical ? this.collabGroup('Local - Methodological', collabs.local.methodical) : ''}
              ${collabs.local?.medical ? this.collabGroup('Local - Medical', collabs.local.medical) : ''}
            </div>
          </section>
        `;
      }

      // Funding
      if (data.funding && data.funding.length) {
        html += `
          <section class="research-section">
            <h2>Funding & Main Grants</h2>
            <ul class="funding-list">
              ${data.funding.map(f => `<li><a href="${f.url}" target="_blank" rel="noopener">${f.name}</a></li>`).join('')}
            </ul>
          </section>
        `;
      }

      this.container.innerHTML = html;
    },

    collabGroup(title, items) {
      return `
        <div class="collab-group">
          <h3>${title}</h3>
          <ul class="collab-list">
            ${items.map(item => `
              <li>${item.url ? `<a href="${item.url}" target="_blank" rel="noopener">${item.name}</a>` : item.name}${item.pis ? ` (${Array.isArray(item.pis) ? item.pis.join(', ') : item.pis})` : ''}</li>
            `).join('')}
          </ul>
        </div>
      `;
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

  // Initialize components based on container presence
  if (document.getElementById('team-grid')) {
    Renderers.TeamGrid.init('team-grid', 'data/people.yaml');
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