import re

# Read original
try:
    with open("orig_radix.html", "r", encoding="utf-16le") as f:
        text_orig = f.read()
except:
    with open("orig_radix.html", "r", encoding="utf-8") as f:
        text_orig = f.read()

# Read current
with open("02_Sorting_Algorithms/radix_sort_animation.php", "r", encoding="utf-8") as f:
    text_now = f.read()

# Extract script block from orig
match = re.search(r'<script>\s*(const maxBars.*?)</script>', text_orig, re.DOTALL)
if match:
    orig_script = match.group(1)
    
    # Let's find where the current code picks up
    # We know the current code has `const algorithmSteps`
    idx_now = text_now.find("const algorithmSteps")
    
    idx_orig = orig_script.find("const algorithmSteps")
    
    missing_prefix = orig_script[:idx_orig]
    
    # We also need to inject `<script>\n` before `missing_prefix`
    # and `\n</script>` at the end of the file!
    # Because they were deleted!
    
    # Just to be safe, I'll prepend the missing_prefix and <script> right before `const algorithmSteps`
    
    new_text = text_now[:idx_now] + "<script>\n" + missing_prefix + text_now[idx_now:]
    
    # Find the end of the file to close the script tag
    body_close = new_text.find("</body>")
    if body_close != -1:
        new_text = new_text[:body_close] + "\n</script>\n" + new_text[body_close:]
    else:
        new_text += "\n</script>\n</body>\n</html>"
        
    with open("02_Sorting_Algorithms/radix_sort_animation.php", "w", encoding="utf-8") as f:
        f.write(new_text)
    
    print("Injected missing JS and exact script tags into radix sort.")
else:
    print("Could not find original script block.")
