# ARAMIS Website

This website is developed using clean HTML, CSS, and vanilla JavaScript. It showcases the research activities, software tools, and team contributions of the ARAMIS Lab. The site is deployed via GitHub Pages from the `docs/` folder.

## Structure

```
docs/
├── index.html              # Homepage
├── people.html             # Team Members
├── publications.html       # Publications
├── software.html           # Software
├── research.html           # Research Topics
├── jobs.html               # Job Offers
├── assets/
│   ├── style.css           # Single stylesheet with CSS variables
│   └── script.js           # JavaScript renderers
├── data/                   # Content data (edit these to update the site)
│   ├── people.yaml
│   ├── publications.yaml
│   ├── software.yaml
│   ├── research.yaml
│   └── jobs.yaml
├── images/                 # Images (team photos, logos, etc.)
├── icons/                  # Icons
├── jobs/                   # Job offer PDFs (organized by year)
└── CNAME                   # Custom domain
```

## How It Works

- **No build step** — Pure static files. Open `docs/index.html` in a browser to preview locally.
- **Data-driven** — Content lives in YAML files in `docs/data/`. JavaScript loads YAML and renders HTML components.
- **GitHub Pages** — Push to `main` branch → automatic deployment.

## Updating Content (No Code Required)

Team members can edit content directly on GitHub.com:

1. Go to the repository on GitHub
2. Navigate to `docs/data/`
3. Click the file to edit (e.g., `people.yaml`)
4. Click the pencil icon ✏️ to edit
5. Make changes, commit directly to `main`
6. Site updates automatically (~1 minute)

### Adding a Team Member

Edit `docs/data/people.yaml`:

```yaml
- name: "First Last"
  role: "Title (Affiliation)"
  category: "faculty"  # faculty | postdocs | phd | engineers | support staff | alumni
  photo: "images/people/carre/first_last.jpg"
  email: "email@domain.org"
  website: "https://personal-website.org"
  scholar: "https://scholar.google.com/citations?user=..."
  linkedin: "https://linkedin.com/in/..."
  twitter: "https://twitter.com/..."
```

- Add photo to `docs/images/people/carre/` (square format, naming: `first-last.jpg`)
- Commit → appears on People page with category filter

### Adding a Publication

Edit `docs/data/publications.yaml`:

```yaml
- title: "Paper Title"
  authors:
    - "Author, First"
    - "Author, Second"
  venue: "Journal Name"
  year: 2025
  doi: "10.1016/j.xxx.2025.xxxxxx"
  pdf: "https://hal.science/hal-xxxxxx/document"
  highlight: true          # optional, highlights in the list
  axis: "neuroimaging-biomarkers"  # for grouping
```

### Adding Software

Edit `docs/data/software.yaml`:

```yaml
- name: "Software Name"
  icon: "images/icons/software/icon.png"
  description: |
    Multi-line description of the software.
    Supports plain text (Markdown not needed here).
  references:
    - title: "Reference paper title"
      venue: "Journal"
      year: 2024
      doi: "10.xxxx/xxxx"
      pdf: "https://..."
  github: "https://github.com/aramis-lab/..."
  website: "https://software-website.org"
  email: "contact@domain.org"
```

### Updating Research Content

Edit `docs/data/research.yaml`. Supports Markdown in text fields:

```yaml
context: |
  Intro paragraph with **bold** and [links](url).

axes:
  - title: "Axis Title"
    pis: ["PI Name 1", "PI Name 2"]
    content: |
      Detailed description with *markdown* support.

collaborations:
  external:
    methodical:
      - name: "Institution"
        pi: "PI Name"
        url: "https://..."
    medical: [...]
  local: { ... }

funding:
  - name: "Grant Name"
    url: "https://..."
```

### Adding a Job Offer

1. Add PDF to `docs/jobs/YYYY/` (create year folder if needed)
2. Edit `docs/data/jobs.yaml`:

```yaml
- title: "PhD: Project Title"
  year: 2025
  month: 9
  category: "phd"          # phd | postdoc | engineer | intern
  duration: "3 years"
  start_date: "2025-10-01"
  contact: "email@domain.org"
  pdf: "jobs/2025/filename.pdf"
  active: true             # false to hide
  description: ""          # optional, shown on job card
```

- Only entries with `active: true` appear on the Jobs page
- Set `active: false` to archive old positions

## Design Customization

Colors and spacing are defined as CSS variables in `docs/assets/style.css`:

```css
:root {
  --color-primary: #281e78;    /* Dark blue */
  --color-secondary: #fa4616;  /* Orange */
  --color-accent: #fa4616;     /* Orange for links */
  --font-family: 'Raleway', system-ui, sans-serif;
  --container-max: 1000px;
}
```

## Local Preview

Simply open `docs/index.html` in a browser — no server required.

## Deployment

1. All changes in `docs/` on `main` branch deploy automatically via GitHub Pages
2. Check deployment status in repo Settings → Pages
3. Custom domain configured via `CNAME` file

## Legacy Content

The old WordPress-exported pages in `docs/pages/` and `docs/themes/`, `docs/plugins/` are preserved but no longer used. The new site uses only the files listed in the structure above.