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

## Quick Updates (Most Common Changes)

These are the files that change most often. Team members edit them via **Pull Requests**.

### 1. Adding a Team Member

**Edit `docs/data/people.yaml`:**

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

**Steps:**
1. Add square photo to `docs/images/people/carre/` (naming: `first-last.jpg`)
2. Open `docs/data/people.yaml` on GitHub → pencil icon ✏️
3. Add entry at appropriate position (categories grouped)
4. **Follow the PR workflow below**

### 2. Adding a Job Offer

**Edit `docs/data/jobs.yaml`:**

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

**Steps:**
1. Add PDF to `docs/jobs/YYYY/` (create year folder if needed)
2. Open `docs/data/jobs.yaml` on GitHub → pencil icon ✏️
3. Add entry at top (sorted by date)
4. **Follow the PR workflow below**
5. Only entries with `active: true` appear on the Jobs page
6. Set `active: false` to archive old positions

---

## Advanced Updates (Maintainers)

These files change less frequently. Require familiarity with YAML structure.

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

Edit `docs/data/research.yaml` (supports Markdown in text fields):

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

---

## Pull Request Workflow

**All changes must go through a Pull Request — no direct commits to `main`.**

### How to create a PR on GitHub:

1. Go to the repository on GitHub
2. Navigate to `docs/data/` → click the file to edit (e.g., `people.yaml`)
3. Click the pencil icon ✏️ to edit
4. Make your changes
5. At the bottom, select **"Create a new branch for this commit and start a pull request"**
6. Enter a short branch name (e.g., `add-member-john-doe`)
7. Click **"Propose changes"**
8. On the next page, click **"Create pull request"**
9. Add a title and description, then click **"Create pull request"**
10. Request review from a maintainer (add as reviewer)
11. Once approved & merged → GitHub Pages auto-deploys (~1 minute)

This ensures changes are reviewed and avoids accidental breaks.

---

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