<?php
$page_title = 'Our Story';
$page_desc  = 'Three generations of Persian-Mediterranean cooking in York. The story of Saffron &amp; Salt.';
$body_class = 'page-about';
require 'includes/header.php';
?>

<main>
  <div class="page-hero">
    <div class="container">
      <p class="section-label">Where we came from</p>
      <h1>Our Story</h1>
    </div>
  </div>

  <!-- Inverse pyramid: lead with the most compelling fact -->
  <section class="section">
    <div class="container">
      <div class="about-lead reveal">
        <p>
          Three generations. One kitchen. Every dish here began as a handwritten note in a notebook Maryam Shirazi
          kept in Tehran in the 1960s — recipes that travelled with her son Dariush when he moved to York in 1998,
          and that now live in the hands of his daughter Layla.
        </p>
        <p style="margin-top:1.25rem;font-size:1rem;color:var(--text-muted);font-family:var(--font-sans);">
          Saffron &amp; Salt opened on Gillygate in 2012. We're still a family restaurant. Still thirty covers. Still the same notebooks.
        </p>
      </div>
    </div>
  </section>

  <!-- Story detail -->
  <section class="section" style="background:var(--midnight-2);border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
    <div class="container">
      <div class="about-story reveal">
        <div class="about-story-text">
          <p class="section-label">The family</p>
          <h2>From Tehran to the Shambles</h2>
          <p>
            Maryam Shirazi cooked for her family in Tehran the way most Persian women of her generation did — by feel,
            by memory, by smell. She measured saffron in pinches and time in seasons. When Dariush left for England
            in 1998, she pressed her notebooks into his hands.
          </p>
          <p>
            For seven years Dariush cooked her dishes for friends, then at the Shambles Market pop-up he ran
            on Saturdays. The queues grew. By 2012 he'd found a small premises on Gillygate — twenty covers,
            a single hob, and every dish made to order.
          </p>
          <p>
            Layla grew up in that kitchen. She trained at Le Cordon Bleu in London, spent a year cooking in Beirut,
            and came home to York in 2018 to take over the kitchen. She hasn't changed the recipes. She's just
            learned what her grandmother already knew.
          </p>
        </div>
        <div style="position:relative;">
          <img src="images/about-family.jpg" alt="Dariush and Layla Shirazi in the kitchen" loading="lazy"
            style="width:100%;height:440px;object-fit:cover;border-radius:4px;">
        </div>
      </div>
    </div>
  </section>

  <!-- Team -->
  <section class="section">
    <div class="container">
      <div class="text-center reveal" style="margin-bottom:3rem;">
        <p class="section-label">The team</p>
        <h2>The people behind the plates</h2>
      </div>

      <div class="team-grid">
        <div class="team-card reveal">
          <img src="images/team-layla.jpg" alt="Layla Shirazi — Head Chef" loading="lazy">
          <h3>Layla Shirazi</h3>
          <p class="role">Head Chef &amp; Co-owner</p>
          <p>Le Cordon Bleu–trained. Spent a year cooking in Beirut before returning to York. Reads her grandmother's notebooks every morning.</p>
        </div>
        <div class="team-card reveal">
          <img src="images/team-dariush.jpg" alt="Dariush Shirazi — Founder" loading="lazy">
          <h3>Dariush Shirazi</h3>
          <p class="role">Founder</p>
          <p>Came to York in 1998 with a suitcase and his mother's recipes. Runs front of house. Knows every regular by name.</p>
        </div>
        <div class="team-card reveal">
          <img src="images/team-tom.jpg" alt="Tom Hargreaves — Sous Chef" loading="lazy">
          <h3>Tom Hargreaves</h3>
          <p class="role">Sous Chef</p>
          <p>From Harrogate. Has worked in the kitchen since 2019 and can now recite the Khoresh recipe from memory.</p>
        </div>
        <div class="team-card reveal">
          <img src="images/team-sara.jpg" alt="Sara Okafor — Restaurant Manager" loading="lazy">
          <h3>Sara Okafor</h3>
          <p class="role">Restaurant Manager</p>
          <p>Ran the floor at restaurants in Leeds and Edinburgh before joining us in 2021. Her calm keeps the whole room right.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Timeline -->
  <section class="section" style="background:var(--midnight-2);border-top:1px solid var(--border);">
    <div class="container">
      <div style="max-width:640px;margin:0 auto;">
        <div class="text-center reveal" style="margin-bottom:3.5rem;">
          <p class="section-label">How we got here</p>
          <h2>A brief history</h2>
        </div>

        <div class="timeline">
          <div class="timeline-item reveal">
            <p class="timeline-year">1998</p>
            <h3>Dariush arrives in York</h3>
            <p>He takes a job washing dishes at a restaurant on Stonegate, cooks Persian food for housemates at weekends.</p>
          </div>
          <div class="timeline-item reveal">
            <p class="timeline-year">2005</p>
            <h3>The Shambles pop-up</h3>
            <p>Saturday mornings at the Shambles Market. Lamb skewers and herb flatbreads. The queue reaches the end of the alley by 11am.</p>
          </div>
          <div class="timeline-item reveal">
            <p class="timeline-year">2012</p>
            <h3>Saffron &amp; Salt opens on Gillygate</h3>
            <p>Twenty covers, one hob, handwritten menus. Fully booked on the second weekend. Dariush calls his mother to tell her.</p>
          </div>
          <div class="timeline-item reveal">
            <p class="timeline-year">2018</p>
            <h3>Layla takes over the kitchen</h3>
            <p>Returns from Beirut, where she trained under Kamal Mouzawak. Introduces the tasting menu and expands to thirty covers.</p>
          </div>
          <div class="timeline-item reveal">
            <p class="timeline-year">2019</p>
            <h3>Michelin Bib Gourmand</h3>
            <p>Recognised for "exceptionally good food at moderate prices." Dariush frames the page. It hangs by the kitchen door.</p>
          </div>
          <div class="timeline-item reveal">
            <p class="timeline-year">2024</p>
            <h3>Seasonal supper clubs</h3>
            <p>Monthly events built around a single ingredient or a passage from Maryam's notebooks. Tickets sell out in hours.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Suppliers -->
  <section class="section--tight">
    <div class="container">
      <div class="text-center reveal" style="margin-bottom:2rem;">
        <p class="section-label">Where the food comes from</p>
        <h2>Our suppliers</h2>
      </div>
      <div class="suppliers-strip reveal">
        <span class="supplier">Rievaulx Farm, North Yorkshire — lamb</span>
        <span class="supplier">Sunny Bank Organics, Skipton — seasonal vegetables</span>
        <span class="supplier">Yorkshire Fisheries, Scarborough — daily catch</span>
      </div>
    </div>
  </section>

</main>

<?php require 'includes/footer.php'; ?>
