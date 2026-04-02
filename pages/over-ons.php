<?php require "includes/header.php" ?>
<main>
    <img src="/assets/images/banner.jpeg" alt="" width="1200">
    <h2>Over Rydr.</h2>
    <div class="grid">
        <div class="row">
            <p>Ons hoofdkantoor bevindt zich in het bruisende hart van Rotterdam, direct naast het Centraal Station.
                Hier combineren we technologie, design en klantgerichtheid onder één dak.</p>
            <p> In een modern pand met uitzicht op de skyline werken we elke dag aan de mobiliteit van morgen. Loop je
                een keer binnen? De koffie staat klaar.<br><br>
                wij geloven in een toekomst waarin mobiliteit simpel, toegankelijk en duurzaam is. <br>
                Met een goede atmosfeer rond het kantoor. </p>
        </div>
        <div class="row">
            <img src="/assets/images/work-place.WEBP" alt="" width="400">
        </div>
    </div>
    <div class="team">
        <h2><i>Ons Team:</i></h2>

        <div class="team-grid">

            <div class="member">
                <img src="/assets/images/team/brian-mensah.WEBP" alt="Brian Mensah, CEO van Rydr" width="200">
                <h3>Brian Mensah</h3>
                <p><strong>CEO</strong></p>
                <p>Stuurt de visie van Rydr en bouwt aan de toekomst van mobiliteit.<br>
                een cruciale rol in de ondersteuning van verschillende bedrijfsfuncties, variërend van HR tot administratie.<br>
                hij zorgt ervoor dat interne processen efficiënt verlopen en bieden de benodigde ondersteuning aan medewerkers om hun taken uit te voeren.</p>
            </div>

            <div class="member">
                <img src="/assets/images/team/jasper-van-den-brink.WEBP" alt="Jasper van den Brink, CTO van Rydr" width="200">
                <h3>Jasper van den Brink</h3>
                <p><strong>CTO</strong></p>
                <p>Leidt de technologische ontwikkeling en zorgt voor schaalbaarheid van het platform.<br>
                    de Data & Analytist bij Rydr is als een krachtcentrale van slimme data-expert is een persoon met een diepgaande passie voor de zakelijke waarde van data.<br>
                    Voor onze klanten zorgen ze ervoor dat mobiel bankieren en dat betalingen soepel verlopen. het belangrijkste is dat hun gegevens altijd veilig zijn.<br>
                    Met een scherp oog voor detail en een analytische geest, transformeert deze Data & Analytist complexe gegevens in waardevolle inzichten die de groei van Rydr stimuleren.</p>
            </div>

            <div class="member">
                <img src="/assets/images/team/lotte-de-graaf.WEBP" alt="Lotte de Graaf, Lead Designer bij Rydr" width="200">
                <h3>Lotte de Graaf</h3>
                <p><strong>Lead Designer</strong></p>
                <p>Ontwerpt intuïtieve en moderne gebruikerservaringen.<br>
                De Marketingafdeling bij Rydr is als de spil in een dynamische machine.<br>
                Haar taak? Zorgen dat Rydr online gezien wordt en klanten aantrekt.<br>
                Dit betekent digitale marketingstrategieën bedenken, online kanalen beheren, de merkidentiteit bewaken en onze producten in de spotlight zetten.<br>
                Ze houden zich bezig met het opstellen van risicobeleid, zorgen dat we aan alle regels voldoen en houden een oogje op de operationele risico's </p>
            </div>

            <div class="member">
                <img src="/assets/images/team/youssef-amrani.WEBP" alt="Youssef Amrani, Marketing Manager bij Rydr" width="200">
                <h3>Youssef Amrani</h3>
                <p><strong>Marketing Manager</strong></p>
                <p>Verbindt het merk Rydr met klanten en de markt.<br>
                De Marketingafdeling bij Rydr is als de spil in een dynamische machine<br>
                Ze zetten alles op alles zodat klanten hun geldzaken soepel en veilig kunnen regelen.</p>
            </div>

        </div>
    </div>
    <div class="form-container">
        <h2><i>Contacteer ons:als geintreseerd bent met werken bij Rydr</i></h2>
        <form action="mailto:info@rydr.nl" method="post" enctype="text/plain">
            <input type="text" name="naam" placeholder="Naam" required>
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="tel" name="telefoon" placeholder="Telefoonnummer" required>
            <button type="submit" style = "width: 100%; padding: 12px; background: linear-gradient(135deg, #007BFF, #00c6ff); color: white; border: none; border-radius: 8px; font-size: 16px; cursor: pointer; transition: 0.3s;">Versturen</button>
            
        </form>
    </div>
</main>
<style>
.form-container 
{
    background: #ffffff;
    padding: 30px;
    border-radius: 12px;
    width: 350px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    animation: fadeIn 0.6s ease-in-out;
  }
  h2 
  {
    color: #3563E9;
  }
    input 
    {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s;
    }
</style>
<?php require "includes/footer.php" ?>
