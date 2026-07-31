import re

def fix_controller(filepath, is_admin=False):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Remove all ->where('agent_id', $agentId)
    content = content.replace("->where('agent_id', $agentId)", "")
    
    # Remove AgentMedia::where('agent_id', $agentId)->
    content = content.replace("AgentMedia::where('agent_id', $agentId)->", "AgentMedia::")
    
    # Remove AgentMedia::where('agent_id', $agentId)\n
    content = re.sub(r"AgentMedia::where\('agent_id',\s*\$agentId\)\s*->", "AgentMedia::", content)

    # Change upload path
    if is_admin:
        content = content.replace("'uploads/admin_gallery'", "'uploads/shared_gallery'")
        content = content.replace("'uploads/admin_gallery/'", "'uploads/shared_gallery/'")
    else:
        content = content.replace("'uploads/agent_gallery/' . $agentId", "'uploads/shared_gallery'")
        content = content.replace("'uploads/agent_gallery/' . $agentId . '/'", "'uploads/shared_gallery/'")
        content = content.replace("'uploads/agent_gallery/'", "'uploads/shared_gallery/'")

    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
        
fix_controller('app/Http/Controllers/AgentController.php', is_admin=False)
fix_controller('app/Http/Controllers/AdminController.php', is_admin=True)

print("Fixed controllers")
