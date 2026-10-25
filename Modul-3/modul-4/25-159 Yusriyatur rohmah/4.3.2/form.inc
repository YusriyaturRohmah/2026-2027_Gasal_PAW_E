<h3>Register</h3>
<fieldset>
  <legend>Person Details</legend>
  <!-- action mengarah ke file processData_form.php (Self-Submission) -->
  <form name="myform" action="processData_form.php" method="POST">
    
    <div class="field">
      <label for="surname">Surname</label>
      <input type="text" name="surname" size="31" value="<?php echo isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : ''; ?>" />
    </div>

    <div class="field">
      <label for="email">Email address</label>
      <input type="text" name="email" size="31" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
    </div>

    <div class="field">
      <label for="passwd">Password</label>
      <input type="password" name="passwd" size="31" />
    </div>

    <div class="field">
      <label for="address">Street Address</label>
      <textarea name="address" rows="3" cols="22"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
    </div>

    <div class="field">
      <label for="state">State</label>
      <select name="state">
        <?php $state = isset($_POST['state']) ? $_POST['state'] : '-1'; ?>
        <option value="-1" <?php echo ($state == '-1') ? 'selected' : ''; ?>>[choose your state]</option>
        <option value="1" <?php echo ($state == '1') ? 'selected' : ''; ?>>Australian Capital Territory</option>
        <option value="2" <?php echo ($state == '2') ? 'selected' : ''; ?>>Queensland</option>
        <option value="3" <?php echo ($state == '3') ? 'selected' : ''; ?>>Victoria</option>
        <option value="4" <?php echo ($state == '4') ? 'selected' : ''; ?>>New South Wales</option>
        <option value="5" <?php echo ($state == '5') ? 'selected' : ''; ?>>Tasmania</option>
        <option value="6" <?php echo ($state == '6') ? 'selected' : ''; ?>>South Australia</option>
        <option value="7" <?php echo ($state == '7') ? 'selected' : ''; ?>>Western Australia</option>
        <option value="8" <?php echo ($state == '8') ? 'selected' : ''; ?>>Northern Territory</option>
      </select>
    </div>

    <div class="field">
      <input type="hidden" name="country" value="Australia" />
    </div>

    <div class="field">
      <label for="sex">Gender</label>
      <?php $sex = isset($_POST['sex']) ? $_POST['sex'] : ''; ?>
      <input type="radio" name="sex" value="male" <?php echo ($sex == 'male') ? 'checked' : ''; ?> />Male
      <input type="radio" name="sex" value="female" <?php echo ($sex == 'female') ? 'checked' : ''; ?> />Female
    </div>

    <div class="field">
      <label for="vegetarian">Vegetarian?</label>
      <input type="checkbox" name="vegetarian" <?php echo isset($_POST['vegetarian']) ? 'checked' : ''; ?> />
    </div>

    <div class="field">
      <label>&nbsp;</label>
      <input type="submit" value="Submit" name="submit" />
      <input type="reset" value="Reset" name="reset" />
    </div>

  </form>
</fieldset>