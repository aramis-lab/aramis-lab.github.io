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
│   ├── jobs.yaml
│   └── aramis_former_members.csv
├── images/                 # Images (team photos, logos, etc.)
│   ├── icons/              # Icons (institution logos, software icons)
│   ├── people/             # Team member photos
│   └── team/               # Team photos (2025 retreat)
├── job_offers/             # Job offer PDFs (organized by year)
├── perso/                  # Personal pages (Olivier Colliot)
├── apprimage/              # APPRIMAGE project page
├── CNAME                   # Custom domain
```

## How It Works

- **No build step** — Pure static files. Open `docs/index.html` in a browser to preview locally.
- **Data-driven** — Content lives in YAML/CSV files in `docs/data/`. JavaScript loads data and renders HTML components.
- **GitHub Pages** — Push to `main` branch → automatic deployment.

## Quick Updates (Most Common Changes)

These are the files that change most often. Team members edit them via **Pull Requests**.

### 1. Adding a Team Member

**Edit `docs/data/people.yaml`:**

```yaml
- name: "First Last"
  role: "Title (Affiliation)"
  category: "faculty"  # faculty | postdocs | phd | interns | support staff
  photo: "images/people/first_last.jpg"
  email: "email@domain.org"
  website: "https://personal-website.org"
  scholar: "https://scholar.google.com/citations?user=..."
  linkedin: "https://linkedin.com/in/..."
  twitter: "https://twitter.com/..."
```

**Photo:** Add square photo to `docs/images/people/` (naming: `first_last.jpg`)

**Steps:**
1. Add square photo to `docs/images/people/`
2. Open `docs/data/people.yaml` on GitHub → pencil icon ✏️
3. Add entry at appropriate position (categories grouped)
4. **Follow the PR workflow below**

### 2. Adding a Job Offer

**Edit `docs/data/jobs.yaml`:**

```yaml
- title: "PhD: Project Title"
  year: 2026
  month: 9
  category: "phd"          # phd | postdoc | engineer | intern
  duration: "3 years"
  start_date: "2026-10-01"
  contact: "email@domain.org"
  pdf: "job_offers/2026/filename.pdf"
  active: true             # false to hide
  description: ""          # optional, shown on job card
```

**Steps:**
1. Add PDF to `docs/job_offers/YYYY/` (create year folder if needed)
2. Open `docs/data/jobs.yaml` on GitHub → pencil icon ✏️
3. Add entry at top (sorted by date)
4. **Follow the PR workflow below**
5. Only entries with `active: true` appear on the Jobs page
6. Set `active: false` to archive old positions

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
7. On the next page, click **"Create pull request"**
8. Add a title and description, then click **"Create pull request"**
9. Request review from a maintainer (add as reviewer)
10. Once approved & merged → GitHub Pages auto-deploys (~1 minute)

This ensures changes are reviewed and avoids accidental breaks.

---

## Advanced Updates (Maintainers)

These files change less frequently. Require familiarity with YAML structure.

### Adding a Publication

Edit `docs/data/publications.yaml`:

```yaml
- title: "Paper Title"
  authors:
    - "LastName1, FirstName1"
    - "LastName2, FirstName2"
  venue: "Full Journal/Conference Name"
  year: 2026
  volume: "10"              # optional
  pages: "1-15"             # optional
  doi: "10.1234/xxxxx"
  pdf: "https://hal.science/hal-xxxxxx/document"  # optional
  axis: "representation-learning"  # one of 6 axes below
```

**Valid axis values:**
- `representation-learning` — Representation Learning for Multimodal Medical Data
- `disease-progression` — Modelling Disease Progression from Longitudinal Data
- `methodological-challenges` — Addressing Methodological Challenges of Real-World Data
- `computational-pathology` — Computational Pathology and High-Content Microscopy
- `reproducibility-validation` — Reproducibility, Benchmarking and Validation
- `clinical-translation` — Translating Computational Innovation into Medical Research and Clinical Practice

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
  international:
    - name: "Institution"
      pi: "PI Name"
      focus: "Research focus"
      url: "https://..."
  national:
    - name: "Institution"
      pi: "PI Name"
      focus: "Research focus"
      url: "https://..."
  local:
    - name: "Institution"
      pi: "PI Name"
      focus: "Research focus"
      url: "https://..."

funding:
  - name: "Funding body, Funding programme, Project <a href=\"url\" target=\"_blank\" rel=\"noopener\">ACRONYM</a>"
    url: "https://..."
```

### Updating Alumni

The alumni list is generated from `docs/data/aramis_former_members.csv`. Edit the CSV file with columns: `Name,Status,Year left`.

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

## Separate Pages

The `docs/apprimage/` (APPRIMAGE project page) and `docs/perso/` (Olivier Colliot personal page) are separate standalone pages hosted alongside the main site. They are not part of the main team website structure but are deployed alongside it.