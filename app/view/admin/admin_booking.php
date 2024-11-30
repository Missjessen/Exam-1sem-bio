<h1>Administrer Bookinger</h1>
<form method="GET" action="">
    <input type="text" name="search" placeholder="Søg booking (e-mail eller telefon)" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <button type="submit">Søg</button>
</form>

<table>
    <tr>
        <th>Kunde</th>
        <th>Parkeringsplads</th>
        <th>Film</th>
        <th>Tidspunkt</th>
        <th>Pladser</th>
        <th>Pris</th>
        <th>Handlinger</th>
    </tr>
    <?php foreach ($bookings as $booking): ?>
        <tr>
            <td><?= htmlspecialchars($booking['customer_name']); ?></td>
            <td><?= ucfirst(htmlspecialchars($booking['screen'])); ?></td>
            <td><?= htmlspecialchars($booking['movie_title']); ?></td>
            <td><?= htmlspecialchars($booking['booking_time']); ?></td>
            <td><?= htmlspecialchars($booking['spots_booked']); ?></td>
            <td><?= htmlspecialchars($booking['total_price']); ?> DKK</td>
            <td>
                <button onclick="openInvoicePopup(<?= $booking['id']; ?>)">Faktura</button>
                <a href="?edit_booking_id=<?= $booking['id']; ?>">Rediger</a>
                <a href="?delete_booking_id=<?= $booking['id']; ?>" onclick="return confirm('Er du sikker?');">Slet</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Popup til faktura -->
<div id="invoice-popup" style="display:none; position:fixed; top:10%; left:20%; width:60%; height:70%; background:#fff; border:1px solid #ccc; padding:20px; z-index:1000; box-shadow:0 0 10px rgba(0,0,0,0.5);">
    <button onclick="closeInvoicePopup()" style="float:right;">✖</button>
    <div id="invoice-content">
        <!-- Indholdet af fakturaen indlæses her dynamisk -->
    </div>
    <button onclick="printInvoice()">Print</button>
</div>

<script>
function openInvoicePopup(bookingId) {
    const popup = document.getElementById('invoice-popup');
    const content = document.getElementById('invoice-content');
    popup.style.display = 'block';
    content.innerHTML = 'Henter faktura...';

    // Hent faktura-data via AJAX
    fetch(`invoice.php?booking_id=${bookingId}`)
        .then(response => response.text())
        .then(data => {
            content.innerHTML = data;
        })
        .catch(err => {
            content.innerHTML = 'Kunne ikke hente faktura.';
        });
}

function closeInvoicePopup() {
    document.getElementById('invoice-popup').style.display = 'none';
}

function printInvoice() {
    const content = document.getElementById('invoice-content').innerHTML;
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    printWindow.document.write(content);
    printWindow.document.close();
    printWindow.print();
}
</script>
