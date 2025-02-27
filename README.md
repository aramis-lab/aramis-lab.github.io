# ARAMIS Website

This website is developed using HTML, CSS, and JavaScript. It is designed to showcase the 
research activities, software tools, and team contributions of ARAMIS. The structure of 
the website is organized as follows:

```
├── apprimage.html
├── docs/
│   ├── publications.html
│   ├── job_offers.html
│   ├── people.html
│   ├── research_topics.html
│   └── software.html
└── index.html
```

## Contributing

This website is designed to be collaborative. We encourage every team member to submit a 
[Pull Request (PR)](https://github.com/aramis-lab/aramis-lab.github.io/pulls) if they notice any issues or wish to propose a new feature or improvement. 
For those unfamiliar with the process, refer to [GitHub's guide on creating a pull request](https://docs.github.com/en/github/collaborating-with-pull-requests/proposing-changes-to-your-work-with-pull-requests/creating-a-pull-request)
for detailed instructions. Several additions are possible on the site.

Please adhere to the following guidelines to maintain consistency in style:

### 1 - Adding a Team Member

To add a team member, update the `docs/pages/people.html` page using the following template:

```html
<!-- Example HTML code for a team member -->
<div class="tmm_member people_border">
  <div class="tmm_photo peopleimg tmm_pic_phd-students_12" style="background: url(../images/people/carre/unknown.png);"></div>
  <div class="tmm_textblock">
    <div class="tmm_names">
      <span class="tmm_fname">Léo</span> 
      <span class="tmm_lname">Guillon</span>
    </div>
    <div class="tmm_scblock">
      <a target="_blank" class="tmm_sociallink" href="https://fr.linkedin.com/">
	<img alt="" src="../plugins/team-members/inc/img/links/linkedin.png"/>
      </a>
      <a class="tmm_sociallink" href="mailto:hugues.roy@icm-institute.org">
	<img alt="" src="../plugins/team-members/inc/img/links/email.png"/>
      </a>
      <a target="_blank" class="tmm_sociallink" href="https://www.researchgate.net/">
	<img alt="" src="../plugins/team-members/inc/img/links/researchgate.png"/>
      </a>
      <a target="_blank" class="tmm_sociallink" href="https://scholar.google.com/" title="Google Scholar">
	<img alt="Google Scholar" src="../plugins/team-members/inc/img/links/customlink.png"/>
      </a>
      <a target="_blank" class="tmm_sociallink" href="aramis">
	<img alt="" src="../plugins/team-members/inc/img/links/website.png"/>
      </a>
      <a target="_blank" class="tmm_sociallink" href="https://twitter.com/"> <!-- a bannir-->
	<img alt="" src="../plugins/team-members/inc/img/links/twitter.png"/>
      </a>
    </div>
  </div>
</div>
```

And add the photo in `docs/images/people/carre/new_member.jpg` (ensure it’s in a square format).

### 2 - Adding a Publication 

To add a publication, update the `docs/pages/publications.html` page using the following template:

```html
<!-- Example HTML code for a publication -->
<li>
    Bottani, Simona, Ninon Burgos, Aurélien Maire, Dario Saracino, Sebastian Stroër, Didier Dormont, and Olivier Colliot. 
    2023. 
    ‘Evaluation of MRI-Based Machine Learning Approaches for Computer-Aided Diagnosis of Dementia in a Clinical Data Warehouse’. 
    <i>Medical Image Analysis</i> 89:102903. 
    doi: <a href="https://doi.org/10.1016/j.media.2023.102903">10.1016/j.media.2023.102903</a>.
    <a href="https://hal.science/hal-03656136/document" target="_blank" rel="noopener noreferrer"><img decoding="async" loading="lazy" src="../images/icons/pdf/pdf_logo.png" alt="Paper in pdf" width="20" height="17"/></a>
</li>
```

### 3 - Adding a Job Offer

To add a job offer, update the `docs/pages/job_offers.html` page using the following template:

```html
<!-- Example HTML code for a publication -->
<br>
  <li>
    <a href="job_offers/2025/01/JobOffer_ARAMIS.pdf">Name of the job offer</a> <br>
    <span style="font-weight: bold;">Duration:</span> x months &#8211; 
    <span style="font-weight: bold;">Starting date:</span>  01/01/2025 &#8211; 
    <span style="font-weight: bold;">Contact:</span> name.lastname@icm-instituite.org
  </li>
```

