function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('address')  then
  if span.classes:includes('street') then
    span.content = pandoc.Str('xxxxxxx')
    local value, position = span.classes:find('street')
    span.classes:remove(position)
  elseif span.classes:includes('square') then
    span.content = pandoc.Str('xxxxxxx')
    local value, position = span.classes:find('square')
    span.classes:remove(position)
  elseif span.classes:includes('housenumber') then
    span.content = pandoc.Str('xx')
    local value, position = span.classes:find('housenumber')
    span.classes:remove(position)  
  elseif span.classes:includes('zipcode') then
    span.content = pandoc.Str('xxxxx')
    local value, position = span.classes:find('zipcode')
    span.classes:remove(position)
  elseif span.classes:includes('municipality') then
    span.content = pandoc.Str('xxxxx')
    local value, position = span.classes:find('municipality')
    span.classes:remove(position)
  elseif span.classes:includes('province') then
    span.content = pandoc.Str('(xx)')
    local value, position = span.classes:find('province')
    span.classes:remove(position)
  else
    span.content = pandoc.Str('xxxxxxx')
  end
  local value, position = span.classes:find('address')
  span.classes:remove(position)
end
return span
end