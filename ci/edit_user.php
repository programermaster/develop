<script type="text/javascript" src="js/custom.js"></script>
<form class="stdform">
    <input type="hidden" id="data[id]" name="data[id]" value="<?php echo isset($data["id"]) ? $data["id"] : "" ?>" >
    <dl>
        <dt class="par">
            <label>First Name:</label>
        </dt>
        <dd class="field"><input type="text" id="data[first_name]" name="data[first_name]" value="<?php echo isset($data["first_name"]) ? $data["first_name"] : ""?>" ></dd>

        <dt class="par">
            <label>Last Name:</label>
        </dt>
        <dd class="field"><input type="text" id="data[last_name]" name="data[last_name]" value="<?php echo isset($data["last_name"]) ? $data["last_name"] : ""?>" ></dd>

        <dt class="par">
            <label>Username:</label>
        </dt>
        <dd class="field"><input type="text" id="data[username]" name="data[username]" value="<?php echo isset($data["username"]) ? $data["username"] : ""?>" ></dd>

        <dt class="par">
            <label>New Password:</label>
        </dt>
        <dd class="field"><input type="text" id="data[password]" name="data[password]" value="" ></dd>

        <dt class="par">
           <label>New Password:</label>
        </dt>
        <dd class="field">
            <select id="data[id_role]" name="data[id_role]" class="uniformselect">
                <option value="">Choose One</option>
                <?php foreach($roles as $role){?>
                <option <?php if(isset($data["id_role"]) && $role["id"] == $data["id_role"]) echo "selected";?> value="<?php echo $role["id"]?>">
                    <?php echo $role["name"]?>
                </option>
                <?php } ?>
            </select>
        </dd>

        <dt class="par">
             <label>Email:</label>
        </dt>
        <dd class="field"><input type="text" id="data[email]" name="data[email]" value="<?php echo isset($data["email"]) ? $data["email"] : ""?>" ></dd>

        <dt class="par">
             <label>Web:</label>
        </dt>
        <dd class="field"><input type="text" id="data[web]" name="data[web]" value="<?php echo isset($data["web"]) ? $data["web"] : ""?>" ></dd>

        <dt class="par">
               <label>Lang:</label>
        </dt>
        <dd class="field">
            <select id="data[lang]" name="data[lang]" class="uniformselect">
                <option value="">Choose One</option>
                <?php foreach($langs["available_langs"] as $lang){?>
                <option <?php if(isset($data["lang"]) && $lang == $data["lang"]) echo "selected";else if(!isset($data["lang"]) && $lang == $langs["def_lang"]) "selected"?> value="<?php echo $lang?>">
                    <?php echo $lang?>
                </option>
                <?php } ?>
            </select>            
        </dd>
        <dt class="par">
             <label>Status:</label>
        </dt>
        <dd class="field">
            <select id="data[status]" name="data[status]" class="uniformselect">
                <option value="">Choose One</option>
                <option <?php if(isset($data["status"]) && $data["status"] == "A") echo "selected"?> value="A">Active</option>
                <option <?php if(isset($data["status"]) && $data["status"] == "D") echo "selected"?> value="D">Deleted</option>
            </select>
        </dd>
    </dl>    
</form>