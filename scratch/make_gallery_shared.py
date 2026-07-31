import re

def update_controller(filepath, is_admin=False):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Remove where('agent_id', $agentId) from queries
    content = content.replace("->where('agent_id', $agentId)", "")
    content = re.sub(r"where\('agent_id',\s*\$agentId\)->", "", content)
    
    # Also handle AgentMedia::where('agent_id', $agentId) cases where it's the first where
    # Example: AgentMedia::where('agent_id', $agentId)->where('type', 'folder')
    # If we removed where('agent_id', $agentId)->, it would become AgentMedia::where('type', 'folder')
    
    # Wait, the regex `where\('agent_id',\s*\$agentId\)->` covers it if it's chained.
    # What if it's just AgentMedia::where('agent_id', $agentId)->get();
    # It would become AgentMedia::get();
    
    # Change upload path
    if is_admin:
        content = content.replace("'uploads/admin_gallery'", "'uploads/shared_gallery'")
        content = content.replace("'uploads/admin_gallery/'", "'uploads/shared_gallery/'")
    else:
        content = content.replace("'uploads/agent_gallery/' . $agentId", "'uploads/shared_gallery'")
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"Updated {filepath}")

update_controller('app/Http/Controllers/AgentController.php', is_admin=False)
update_controller('app/Http/Controllers/AdminController.php', is_admin=True)
