import re

with open('routes/web.php', 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace(
    "Route::get('/hotels', [AgentController::class, 'hotels'])->name('hotels');",
    "Route::get('/hotels', [AgentController::class, 'hotels'])->name('hotels');\n    Route::post('/hotels/store', [AgentController::class, 'storeHotel'])->name('hotels.store');\n    Route::post('/hotels/update', [AgentController::class, 'updateHotel'])->name('hotels.update');\n    Route::post('/hotels/delete/{id}', [AgentController::class, 'deleteHotel'])->name('hotels.delete');"
)

with open('routes/web.php', 'w', encoding='utf-8') as f:
    f.write(content)
print('Routes patched.')
